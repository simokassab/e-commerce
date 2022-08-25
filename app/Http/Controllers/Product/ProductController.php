<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\MainController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Brand\SelectBrandResource;
use App\Http\Resources\Category\SelectCategoryResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Label\SelectLabelResource;
use App\Http\Resources\Price\SelectPriceResource;
use App\Http\Resources\Product\ProductBundleResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\SelectProductStatusResource;
use App\Http\Resources\Tag\TagResource;
use App\Http\Resources\Tax\SelectTaxResource;
use App\Http\Resources\Unit\SelectUnitResource;
use App\Models\Brand\Brand;
use App\Models\Category\Category;
use App\Models\Field\Field;
use App\Models\Label\Label;
use App\Models\Price\Price;
use App\Models\Product\Product;
use App\Models\Product\ProductCategory;
use App\Models\Product\ProductStatus;
use App\Models\Settings\Setting;
use App\Models\Tag\Tag;
use App\Models\Tax\Tax;
use App\Models\Tax\TaxComponent;
use App\Models\Unit\Unit;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use App\Http\Resources\Product\SelectProductOrderResource;
use App\Http\Resources\Product\SingleProductResource;

class ProductController extends MainController
{
    const OBJECT_NAME = 'objects.product';
    const relations = ['parent', 'children', 'defaultCategory', 'unit', 'tax', 'brand', 'category', 'tags'];

    public function __construct(ProductService $productService)
    {
        $this->productService = $productService;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        if ($request->method() == 'POST') {
            $searchKeys = ['id', 'name', 'sku', 'type', 'quantity', 'status'];

            $searchRelationsKeys['defaultCategory'] = ['categories' => 'name'];

            $categoriesCount = Product::has('category')->count();
            $tagsCount = Product::has('tags')->count();
            $brandsCount = Product::has('brand')->count();

            if ($categoriesCount > 0)
                $searchRelationsKeys['category'] = ['categories' => 'name'];
            if ($tagsCount > 0)
                $searchRelationsKeys['tags'] = ['tags' => 'name'];
            if ($brandsCount > 0)
                $searchRelationsKeys['brand'] = ['brands' => 'name'];

            return $this->getSearchPaginated(ProductResource::class, Product::class, $request, $searchKeys, self::relations, $searchRelationsKeys);
        }

        return $this->successResponsePaginated(ProductResource::class, Product::class, self::relations);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $PriceArray = [];
        $prices = SelectPriceResource::collection(Price::with('currency')->where('is_virtual', 0)->select('id', 'name', 'currency_id')->get());

        foreach ($prices as $price => $value) {
            $object = (object)[];
            $object->id = $value['id'];
            $object->name = $value['name'];
            $object->currency = $value->currency->code . '-' . $value->currency->symbol;
            $PriceArray[] = $object;
        }

        $fields = FieldsResource::collection(Field::with('fieldValue')->whereEntity('product')->where('is_attribute', 0)->get());
        $attributes = FieldsResource::collection(Field::with('fieldValue')->whereEntity('product')->where('is_attribute', 1)->get());
        $tags = TagResource::collection(Tag::all('id', 'name'));
        $labels = SelectLabelResource::collection(Label::whereEntity('product')->select('id', 'title')->get());
        $brands = SelectBrandResource::collection(Brand::all('id', 'name'));
        $units = SelectUnitResource::collection(Unit::all('id', 'name')); // same result as query()->take(['id','name'])->get
        $taxes = SelectTaxResource::collection(Tax::all('id', 'name'));
        $categories = SelectCategoryResource::collection(Category::all('id', 'name'));
        $statuses = SelectProductStatusResource::collection(ProductStatus::all('id', 'name'));

        $nestedCategory = [];
        $categoriesForNested = Category::with('parent')->get();
        $nestedCategories = CategoryService::getAllCategoriesNested($categoriesForNested);

        return $this->successResponse('Success!', [
            'prices' =>  count($PriceArray) != 0 ? $PriceArray : "-",
            'fields' => count($fields) != 0 ? $fields : [],
            'attributes' => count($attributes) != 0 ? $attributes : "-",
            'labels' => count($labels) != 0 ? $labels : "-",
            'tags' => count($tags) != 0 ? $tags : "-",
            'brands' => count($brands) != 0 ? $brands : "-",
            'units' => count($units) != 0 ? $units : "-",
            'taxes' => count($taxes) != 0 ? $taxes : "-",
            'categories' => count($categories) != 0 ? $categories : "-",
            'statuses' => count($statuses) != 0 ? $statuses : "-",
            'nested_categories' => $nestedCategories

        ]);
    }

    public function getAllProductsAndPrices(Request $request)
    {

        $products = Product::with('priceClass', 'price')
            ->when(($request->has('product_name') && $request->product_name != null), function ($query) use ($request) {
                $value = strtolower($request->product_name);
                $query->whereRaw('lower(name) like (?)', ["%$value%"]);
            })
            ->when(($request->has('category') && $request->category != null), function ($query) use ($request) {
                $query->orWhereHas('category', function ($query) use ($request) {
                    $query->where('category_id', $request->category);
                })
                    ->orWhereHas('defaultCategory', function ($query) use ($request) {
                        $query->where('categories.id', $request->category);
                    });
            })
            ->whereNotIn('type', ['variable', 'bundle'])->get();

        return $this->successResponse('Success!', ['products' => ProductBundleResource::collection($products)]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    // public function addproduct(Request $request){
    //     $product = $this->productService->createProduct($request);
    //     return $product;
    // }




    public function store(StoreProductRequest $request)
    {

        // DB::beginTransaction();
        // try {
        $product = $this->productService->createAndUpdateProduct($request);
        $childrenIds = [];
        if ($request->type == 'variable' && ($request->product_variations || count($request->product_variations) > 0)) {
            $childrenIds = $this->productService->storeVariations($request, $product);
        }
        if ($request->type == 'bundle') {
            $this->productService->storeAdditionalBundle($request, $product);
        }
        Product::find($product->id)->updateProductQuantity($request->reserved_quantity, 'add');
        $this->productService->storeAdditionalProductData($request, $product, $childrenIds);

        DB::commit();
        return $this->successResponse([
            'message' => __('messages.success.create', ['name' => __(self::OBJECT_NAME)]),
            'product' =>  new ProductResource($product->load(['defaultCategory', 'tags', 'brand', 'category']))
        ]);

        // }catch (\Exception $ex) {
        //     DB::rollBack();
        //     return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME),]),
        //     'message' => $ex->getMessage()
        //      ]);

        // }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Product $product)
    {
        return $this->successResponse('Success!', ['product' =>  new SingleProductResource($product->load(['defaultCategory', 'tags', 'brand', 'category', 'unit', 'tax', 'priceClass', 'price', 'field', 'labels', 'productRelatedChildren', 'children', 'images']))]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(StoreProductRequest $request, Product $product)
    {

        DB::beginTransaction();
        try {
            // $oldReservedQuantity=Product::find($request->id)->pluck('reserved_quantity')->last();

            $product = $this->productService->createAndUpdateProduct($request, $product);
            $childrenIds = [];

            if ($request->type == 'variable' && ($request->product_variations || count($request->product_variations) > 0)) {
                $childrenIds = $this->productService->storeVariations($request, $product);
            }
            if ($request->type == 'bundle') {
                $this->productService->storeAdditionalBundle($request, $product);
            }
            // Product::find($product->id)->updateProductQuantity($oldReservedQuantity,'sub');


            $this->productService->storeAdditionalProductData($request, $product, $childrenIds);

            DB::commit();
            return $this->successResponse([
                'message' => __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
                'product' =>  new ProductResource($product->load(['defaultCategory', 'tags', 'brand', 'category']))
            ]);
        } catch (\Exception $ex) {
            DB::rollBack();
            return $this->errorResponse([
                'message' => __('messages.failed.create', ['name' => __(self::OBJECT_NAME),]),
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        DB::beginTransaction();
        try {
            $this->productService->deleteRelatedDataForProduct($product);
            $product->delete();
            return $this->successResponse(['message' => __('messages.success.delete', ['name' => __(self::OBJECT_NAME)])]);
            DB::commit();
        } catch (\Exception $ex) {
            DB::rollback();
            return $this->errorResponse(['message' => __('messages.failed.delete', ['name' => __(self::OBJECT_NAME),])]);
        }
    }
    public function toggleStatus(Request $request, $id)
    {

        $request->validate(['is_disabled' => 'boolean|required']);
        $product = Product::findOrFail($id);
        if ($product->type == 'variable') {
            $productChildren = $product->children;
            $productChildrenArray = [];
            foreach ($productChildren as $productChild => $value) {
                $productChildrenArray[$productChild]->is_disabled = $request->is_disabled;
            }
            batch()->update(new Product(), $productChildrenArray, 'id');
        }
        $product->is_disabled = $request->is_disabled;
        if (!$product->save())
            return $this->errorResponse(['message' => __('messages.failed.update', ['name' => __(self::OBJECT_NAME)])]);

        return $this->successResponse([
            'message' => __('messages.success.update', ['name' => __(self::OBJECT_NAME)]),
            'product' =>  new ProductResource($product)
        ]);
    }

    public function updateSortValues(StoreProductRequest $request)
    {
        batch()->update($product = new Product(), $request->order, 'id');
        return $this->successResponsePaginated(ProductResource::class, Product::class, self::relations);
    }

    public function getProductsForOrders(Request $request)
    {
        $name = '';
        if (array_key_exists('name', $request->data)) {
            $name = strtolower($request->data['name']);
        }

        $products = Product::with(['tax', 'pricesList.prices'])->whereRaw('lower(name) like (?)', ["%$name%"])->paginate($request->limit ?? config('defaults.default_pagination'));
        $data['taxComponents'] = TaxComponent::all();
        $data['tax'] = Tax::all();
        return SelectProductOrderResource::customCollection($products, $data);
    }

    public function getTableHeaders()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.products')]);
    }

    public function getTableHeadersForSelect()
    {
        return $this->successResponse('Success!', ['headers' => __('headers.products_select_product')]);
    }
}
