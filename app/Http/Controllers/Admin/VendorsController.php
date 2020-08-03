<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\VendorRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use App\Notifications\VendorCreated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class VendorsController extends Controller
{
    public function index()
    {
        $vendors = Vendor::with('category')->paginate(PAGINATION_COUNT);
        return view('admin.vendors.index', compact('vendors'));
    }

    public function create()
    {

        $categories = MainCategory::where('translation_lang', get_default_lang())->active()->get();
        return view('admin.vendors.create', compact('categories'));

    }

    public function store(VendorRequest $request)
    {


        try {

            if (!$request->has('active')) {
                $request->request->add(['active' => 0]);

            } else {
                $request->request->add(['active' => 1]);
            }
            $file_path = "";
            if ($request->has('logo')) {


                $file_path = uploadImage('vendors', $request->logo);
            };


            $vendor = Vendor::create([
                'name' => $request->name,
                'mobile' => $request->mobile,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'active' => $request->active,
                'address' => $request->address,
                'category_id' => $request->category_id,
                'logo' => $file_path,


            ]);

            Notification::send($vendor, new VendorCreated($vendor));
            return redirect()->route('admin.vendors')->with('success', 'تم إضافه البيانات بنجاح');

        } catch (\Exception $exception) {

            return $exception;
            return redirect()->route('admin.vendors')->with('error', 'هناك خطأ الرجاء المحاوله لاحقا');

        }

    }

    public function edit($id)
    {

        try {
            $vendor = Vendor::with('category')->find($id);
            $categories = MainCategory::where('translation_lang', get_default_lang())->active()->get();


            if (!$vendor) {
                return redirect()->route('admin.vendors')->with('error', 'هذا المتجر غير موجود');

            }
            return view('admin.vendors.edit', compact('vendor', 'categories'));

        } catch (\Exception $exception) {
            return redirect()->route('admin.vendors')->with('error', 'هناك خطأ الرجاء المحاوله لاحقا');

        }


    }

    public function update($id, VendorRequest $request)
    {


        try {
            $vendor = Vendor::find($id);
            if (!$vendor) {
                return redirect()->route('admin.vendors')->with('error', 'هذا المتجر غير موجود');
            }

            DB::beginTransaction();
            if ($request->has('logo')) {
                $file_path = uploadImage('vendors', $request->logo);
                Vendor::where('id', $id)->update([
                    'logo' => $file_path,

                ]);
            };

            $data = $request->except(['_token', '_method', 'id', 'logo', 'password', 'latitude', 'longitude']);
            if ($request->has('password')) {
                $data['password'] = bcrypt($request->password);
            }
            Vendor::where('id', $id)->update($data);
            DB::commit();
            return redirect()->route('admin.vendors')->with('success', 'تم تعديل البيانات بنجاح');


        } catch (\Exception $exception) {

            DB::rollBack();
//            return $exception;
            return redirect()->route('admin.vendors')->with('error', 'هناك خطأ الرجاء المحاوله لاحقا');

        }
    }

    public function changeStatus()
    {


    }

    public function destroy()
    {


    }
}
