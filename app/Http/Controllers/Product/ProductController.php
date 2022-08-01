<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\Category\CategoryController;
use App\Http\Controllers\MainController;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Resources\Brand\SelectBrandResource;
use App\Http\Resources\Category\SelectCategoryResource;
use App\Http\Resources\Field\FieldsResource;
use App\Http\Resources\Field\SelectFieldResource;
use App\Http\Resources\Field\SingleFieldResource;
use App\Http\Resources\Label\LabelsResource;
use App\Http\Resources\Label\SelectLabelResource;
use App\Http\Resources\Price\SelectPriceResource;
use App\Http\Resources\Price\SinglePriceResource;
use App\Http\Resources\Product\ProductResource;
use App\Http\Resources\Product\SelectProductStatusResource;
use App\Http\Resources\SelectTagResource;
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
use App\Models\Product\ProductPrice;
use App\Models\Product\ProductStatus;
use App\Models\Tag\Tag;
use App\Models\Tax\Tax;
use App\Models\Unit\Unit;
use App\Services\Category\CategoryService;
use App\Services\Product\ProductService;
use App\Services\RolesAndPermissions\PermissionsServices;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends MainController
{
    const OBJECT_NAME = 'objects.product';
    const relations=['parent','children','defaultCategory','unit','tax','brand','category','tags'];

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
        if($request->method()=='POST'){
            $searchKeys=['id','name','sku','type','quantity','status'];

            $searchRelationsKeys['defaultCategory'] = ['categories' => 'name'];

            $categoriesCount = Product::has('category')->count();
            $tagsCount = Product::has('tags')->count();
            $brandsCount = Product::has('brand')->count();

            if($categoriesCount>0)
                $searchRelationsKeys['category'] = ['categories' => 'name'];
            if($tagsCount>0)
                $searchRelationsKeys['tags'] = ['tags' => 'name'];
            if($brandsCount>0)
                $searchRelationsKeys['brand'] = ['brands' => 'name'];

            return $this->getSearchPaginated(ProductResource::class, Product::class,$request, $searchKeys,self::relations,$searchRelationsKeys);
        }

        return $this->successResponsePaginated(ProductResource::class,Product::class,self::relations);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $PriceArray=[];
        $prices= SelectPriceResource::collection(Price::with('currency')->where('is_virtual', 0)->select('id','name','currency_id')->get());

        foreach ($prices as $price => $value) {
            $object = (object)[];
            $object->id=$value['id'];
            $object->name=$value['name'];
            $object->currency=$value->currency->code .'-'.$value->currency->symbol;
            $PriceArray[]=$object;
        }

        $fields= FieldsResource::collection(Field::with('fieldValue')->whereEntity('product')->where('is_attribute',0)->get());
        $attributes= FieldsResource::collection(Field::with('fieldValue')->whereEntity('product')->where('is_attribute',1)->get());
        $tags = TagResource::collection(Tag::all('id','name'));
        $labels = SelectLabelResource::collection(Label::whereEntity('product')->select('id','title')->get());
        $brands = SelectBrandResource::collection(Brand::all('id','name'));
        $units = SelectUnitResource::collection(Unit::all('id','name')); // same result as query()->take(['id','name'])->get
        $taxes= SelectTaxResource::collection(Tax::all('id','name'));
        $categories = SelectCategoryResource::collection(Category::all('id','name'));
        $statuses = SelectProductStatusResource::collection(ProductStatus::all('id','name'));

        $nestedCategory = [];
        $categoriesForNested = Category::with('parent')->get();
        $nestedCategories = ProductService::getAllCategoriesNested($categoriesForNested);

        return $this->successResponse('Success!',[
            'prices'=>  count($PriceArray) != 0 ? $PriceArray : "-",
            'fields'=> count($fields) != 0 ? $fields : "-",
            'attributes'=> count($attributes) != 0 ? $attributes : "-",
            'labels'=> count($labels) != 0 ? $labels : "-",
            'tags'=> count($tags) != 0 ? $tags : "-",
            'brands'=> count($brands) != 0 ? $brands : "-",
            'units'=> count($units) != 0 ? $units : "-",
            'taxes'=> count($taxes) != 0 ? $taxes : "-",
            'categories'=> count($categories) != 0 ? $categories : "-",
            'statuses'=>count($statuses) != 0 ? $statuses : "-",
            'nested_categories' => $nestedCategories

        ]);

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function addproduct(Request $request){
        $product = $this->productService->createProduct($request->all());
        return $product;
    }


    public function store(StoreProductRequest $request)
    {
        // DB::beginTransaction();
        // try {
            $product = $this->productService->createProduct($request->all());
            // $childrenIds=[];
            // if($request->type=='variable' && ($request->product_variations || count($request->product_variations) > 0)){
            //    $childrenIds=$this->productService->storeVariationsAndPrices($request,$product);
            // }
            // elseif($request->type=='bundle')
            //     $this->productService->storeAdditionalBundle($request,$product);

            // $this->productService->storeAdditionalProductData($request,$product->id,$childrenIds);
            // return $this->successResponse('Success!',['product'=>$product]);
            return $this->successResponse( __('messages.success.create',
            ['name' => __(self::OBJECT_NAME)]),
            ['product' => new ProductResource($product)]
            );

        // DB::commit();

        // return $this->successResponse( __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        // ['product' =>  new ProductResource($product->load(['defaultCategory','brand','category','tags']))]);

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
        return $this->successResponse(['product' =>  new ProductResource($product)]);

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
     * @return \Illuminate\Http\Response
     */
    public function update(StoreProductRequest $request, Product $product)
    {
        // DB::beginTransaction();
        // try {
            $product->name = json_encode($request->name);
            $product->slug = $request->slug;
            $product->code = $request->code;
            $product->sku = $request->sku;
            $product->type = $request->type;
            $product->quantity = $request->quantity ?? 0;
            $product->reserved_quantity = $request->reserved_quantity ?? 0;
            $product->minimum_quantity = $request->minimum_quantity ?? 0;

            $product->summary = json_encode($request->summary);
            $product->specification = json_encode($request->specification);
            if($request->image)
                $product->image= $this->imageUpload($request->file('image'),config('images_paths.product.images'));

            $product->meta_title = json_encode($request->meta_title);
            $product->meta_description = json_encode($request->meta_description);
            $product->meta_keyword = json_encode($request->meta_keyword);
            $product->description = json_encode($request->description);
            $product->status = $request->status;
            $product->barcode = $request->barcode;
            $product->height = $request->height;
            $product->width = $request->width;
            $product->is_disabled=0;
            $product->length = $request->length;
            $product->weight = $request->weight;
            $product->is_default_child = $request->is_default_child ?? 0;
            $product->parent_product_id = "";
            $product->category_id= $request->category_id;
            $product->unit_id = $request->unit_id;
            $product->brand_id = $request->brand_id;
            $product->tax_id = $request->tax_id;
            $product->products_statuses_id = $request->products_statuses_id;
            $product->save();


            if($request->type=='variable'){
               $this->productService->storeVariations($request,$product->id);
            }
            $this->productService->storeAdditionalProductData($request,$product->id);
        // DB::commit();
        return $this->successResponse(['message' => __('messages.success.create',['name' => __(self::OBJECT_NAME)]),
        'product' =>  new ProductResource($product)
          ]);
    //     }catch (\Exception $ex) {
    //         DB::rollBack();
    //         return $this->errorResponse(['message' => __('messages.failed.create',['name' => __(self::OBJECT_NAME),]),
    //         'message' => $ex->getMessage()
    //          ]);

    // }
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
            return $this->successResponse(['message' => __('messages.success.delete',['name' => __(self::OBJECT_NAME)])]);
            DB::commit();
    } catch (\Exception $ex) {
            DB::rollback();
            return $this->errorResponse(['message' => __('messages.failed.delete',['name' => __(self::OBJECT_NAME),])]);
        }
    }
    public function toggleStatus(Request $request ,$id){

        $request->validate(['is_disabled' => 'boolean|required']);
        $product = Product::findOrFail($id);
        if($product->type=='variable'){
            $productChildren = $product->children;
            $productChildrenArray=[];
            foreach ($productChildren as $productChild => $value) {
                $productChildrenArray[$productChild]->is_disabled=$request->is_disabled;
            }
            batch()->update(new Product(),$productChildrenArray,'id');
        }
        $product->is_disabled=$request->is_disabled;
        if(!$product->save())
            return $this->errorResponse(['message' => __('messages.failed.update',['name' => __(self::OBJECT_NAME)]) ]);

        return $this->successResponse(['message' => __('messages.success.update',['name' => __(self::OBJECT_NAME)]),
            'product' =>  new ProductResource($product)
        ]);

    }

    public function updateSortValues(StoreProductRequest $request){
        batch()->update($product = new Product(),$request->order,'id');
            return $this->successResponsePaginated(ProductResource::class,Product::class,self::relations);
    }



    public function getTableHeaders(){
        return $this->successResponse('Success!',['headers' => __('headers.products') ]);
}

}
