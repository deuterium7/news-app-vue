<div style="display: table; width: 100%; text-align: center; height: 40px; background: #68A4C4; color: #fff; font-family: 'Open Sans', Arial, sans-serif; font-size: 14px; font-weight: bold;">
    <div style="display: table-cell; vertical-align: middle;">{{ trans('catalog.articleCreated') }}!</div>
</div>
<div>
    <div style="width: 50%; padding: 40px 25%; text-align: justify;">
        <h3>{{ trans('catalog.articleContent') }}:</h3>
        <div>{{ trans('catalog.title') }} - <strong>{{ $article->title }}</strong></div>
        <div>{{ trans('catalog.body') }} - <strong>{{ $article->body }}</strong></div>
    </div>
</div>