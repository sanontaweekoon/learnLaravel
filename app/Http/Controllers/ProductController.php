<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Intervention\Image\ImageManagerStatic as Image;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //return Product::all();

        // อ่านข้อมูลแบบแบ่งหน้า
        return Product::orderBy('id', 'desc')->paginate(25);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        // เช็คสิทธิ์ (role) ว่าเป็น admin (1)
        $user = auth()->user();

        if ($user->tokenCan("1")) {
            $request->validate([
                'name' => 'required|min:5',
                'slug' => 'required|',
                'price' => 'required|'
            ]);

            // กำหนดตัวแปรรับค่าจากฟอร์ม
            $data_product = array(
                'name' => $request->input('name'),
                'slug' => $request->input('slug'),
                'description' => $request->input('description'),
                'price' => $request->input('price'),
                'user_id' => $user->id,
                
            );
            // รับไฟล์ภาพเข้ามา
            $image = $request->file('file');

            // เช็คว่าผู้ใช้มีการอัพโหลดภาพเข้ามาหรือไม่
            if(!empty($image)){
                
                // อัพโหลดรูปภาพ
                // เปลี่ยนชื่อรูปที่ได้
                $file_name = "product_".time().".".$image->getClientOriginalExtension();

                // กำหนดขนาดความกว้าง และ สูง ของภาพที่ต้องการย่อขนาด
                $imgWidth  = 400;
                $imgHeight = 400;
                $folderupload = public_path('/images/products/thumbnail');
                $path = $folderupload."/".$file_name;

                // อัพโหลดเข้าสู่ folder thumbnail
                $img = Image::make($image->getRealPath());
                $img->orientate()->fit($imgWidth, $imgHeight, function($constraint){
                    $constraint->upsize();
                });
                $img->save($path);

                // อัพโหลดภาพต้นฉบับเข้า folder original
                $destinationPath = public_path('/images/products/original');
                $image->move($destinationPath, $file_name);

                // กำหนด path เพื่อนำรูปมาใส่ในตารางฐานข้อมูล
                $data_product['image'] = url('/').'/images/products/thumbnail/'.$file_name;
            }else{
                $data_product['image'] = url('/').'/images/products/thumbnail/no_img.jpg';
            }

            return response($data_product, 201);
            //return response($request->all(), 201);
            //return Product::create($request->all());
        } else {
            return [
                'status' => 'Pemision denied to create'
            ];
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return Product::find($id);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $product = Product::find($id);
        $product->update($request->all());

        return $product;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        // เช็คสิทธิ์ (role) ว่าเป็น admin(1)
        $user = auth()->user();

        return Product::destroy($id);

        if ($user->tokenCan("1")) {
            return Product::destroy($id);
        } else {
            return [
                'status' => 'Pemision denied to delete'
            ];
        }
    }
}
