<?php

namespace App\Http\Controllers;

use App\Contracts\Article as ArticleContract;
use App\Contracts\Category as CategoryContract;
use App\Http\Requests\CategoryRequest;
use App\Models\Category;

class CategoryController extends Controller
{
    /**
     * @var CategoryContract
     */
    protected $categories;

    /**
     * @var ArticleContract
     */
    protected $articles;

    /**
     * CategoryController constructor.
     *
     * @param CategoryContract $categories
     * @param ArticleContract  $articles
     */
    public function __construct(CategoryContract $categories, ArticleContract $articles)
    {
        $this->categories = $categories;
        $this->articles = $articles;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = $this->categories->all();

        return view('categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CategoryRequest $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(CategoryRequest $request)
    {
        $put = 'images/categories';
        $image = $request->file('image');

        $upload = public_path($put);
        $filename = time() . '.' . $image->getClientOriginalExtension();
        $image->move($upload, $filename);

        $link = $put .'/'. $filename;

        $this->categories->create([
            'name' => $request->name,
            'image' => $link,
        ]);

        return redirect()->route('admin.categories');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $articles = $this->articles->getArticlesFromCategory($id);

        return view('categories.show', compact('articles'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Models\Category $category
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        return view('categories.edit', compact('category'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CategoryRequest $request
     * @param int             $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(CategoryRequest $request, $id)
    {
        if ($request->hasFile('image')) {
            $put = 'images/categories';
            $image = $request->file('image');

            $upload = public_path($put);
            $filename = time() . '.' . $image->getClientOriginalExtension();
            $image->move($upload, $filename);

            $link = $put .'/'. $filename;

            $this->categories->update($id, [
                'name' => $request->name,
                'image' => $link,
            ]);
        } else {
            $this->categories->update($id, $request->all());
        }

        return redirect()->route('admin.categories');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $this->categories->delete($id);

        return redirect()->back();
    }
}
