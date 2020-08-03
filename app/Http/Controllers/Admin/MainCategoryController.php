<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MainCategoryRequest;
use App\Models\MainCategory;
use App\Models\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use phpDocumentor\Reflection\DocBlock\Tags\Return_;
use function GuzzleHttp\Psr7\str;


class MainCategoryController extends Controller
{
    public function index()
    {

        $default_lang = get_default_lang();


        $categories = MainCategory::where('translation_lang', $default_lang)->selection()->paginate(PAGINATION_COUNT);

        return view('admin.mainCategories.index', compact('categories'));

    }

    public function create()
    {


        return view('admin.mainCategories.create');
    }

    public function store(MainCategoryRequest $request)
    {

        try {


            // عشان يحطلي كل البيانات اللي راجعه في array  واحده

            $cat = Collect($request->category);

            //  عشان يرجعلي البيانات الخاصه باللغه ال default  للموقع فقط

            $filter = $cat->filter(function ($value, $key) {

                return $value['abbr'] == get_default_lang();

            });


            // الصوره
            $file_path = "";
            if ($request->has('photo')) {


                $file_path = uploadImage('mainCategories', $request->photo);
            };
            // عشان احول ال object اللي راجع ل  array

            $default_cat = array_values($filter->all())[0];


            // طريقه   للتخزين في الداتا بيز ولكن  عشان اجيب ال id  ال default للغه بتاع القسم

            DB::beginTransaction();

            $default_category_id = MainCategory::insertGetId([
                'translation_lang' => $default_cat['abbr'],
                'translation_of' => 0,
                'name' => $default_cat['name'],
                'slug' => $default_cat['name'],
                'photo' => $file_path,


            ]);

            //  عشان يرجعلي البيانات الخاصه باللغه لجميع اللغات ماعدا ال default
            $categories = $cat->filter(function ($value, $key) {

                return $value['abbr'] != get_default_lang();

            });

            //تخزين جميع الاقسام باللغات المختلفه في array
            if (isset($categories)) {
                $categories_array = [];
                foreach ($categories as $category) {


                    $categories_array[] = [
                        'translation_lang' => $category['abbr'],
                        'translation_of' => $default_category_id,
                        'name' => $category['name'],
                        'slug' => $category['name'],
                        'photo' => $file_path,
                    ];

                }
                //تخزين جميع الاقسام باللغات الاخري
                MainCategory::insert($categories_array);


            }

            DB::commit();
            return redirect()->route('admin.mainCategories')->with('success', 'تم إضافه البيانات بنجاح');

        } catch (\Exception $exception) {

            DB::rollback();
            return redirect()->route('admin.mainCategories')->with('error', 'حدث خطأ برجاء المحاوله لاحقا');
        }

    }

    public function edit($id)
    {


        // get specific categories with its translations
        $mainCategory = MainCategory::with('categories')->selection()->find($id);
//        return $category;

        if (!$mainCategory) {
            return redirect()->route('admin.mainCategories')->with('error', 'هذا القسم غير موجود');
        }
        return view('admin.mainCategories.edit', compact('mainCategory'));

    }

    public function update($id, MainCategoryRequest $request)
    {


        try {


            $main_category = MainCategory::find($id);

            if (!$main_category) {
                return redirect()->route('admin.mainCategories')->with('error', 'هذا القسم غير موجود');

            }
            $category = array_values($request->category)[0];


            if (!$request->has('category.0.active')) {
                $request->request->add(['active' => 0]);

            } else {
                $request->request->add(['active' => 1]);
            }
            MainCategory::where('id', $id)->update([
                'name' => $category['name'],
                'active' => $request->active,

            ]);// save image


            if ($request->has('photo')) {

                // delete the old photo from the folder
                $image = Str::after($main_category->photo, 'assets/');
                $image = base_path('assets/' . $image);
                unlink($image);

                // save the newest photo
                $filePath = uploadImage('mainCategories', $request->photo);
                MainCategory::where('id', $id)->update([
                    'photo' => $filePath,
                ]);
            }


            return redirect()->route('admin.mainCategories')->with('success', 'تم  تحديث البيانات بنجاح');
        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'حدث خطأ برجاء المحاوله لاحقا');

        }


    }

    public function destroy($id)
    {

        try {
            $category = MainCategory::find($id);

            if (!$category) {
                return redirect()->route('admin.mainCategories')->with('error', 'هذا القسم غير موجود');
            }
            $vendors = $category->vendors();
            if (isset($vendors) && $vendors->count() > 0) {
                return redirect()->back()->with('error', 'لا يمكن  حذف هذا القسم');
            }
            // delete the  photo from the folder

            $image = Str::after($category->photo, 'assets/');
            $image = base_path('assets/' . $image);
            unlink($image);

            $category->delete();
            return redirect()->route('admin.mainCategories')->with('success', 'تم  حذف البيانات بنجاح');

        } catch (\Exception $exception) {
            return redirect()->back()->with('error', 'حدث خطأ برجاء المحاوله لاحقا');


        }

    }
}
