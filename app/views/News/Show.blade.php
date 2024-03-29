@extends('Main.Boilerplate')

@section('title')
  <title>{{{ $news->title }}} - {{ trans('main.brand') }}</title>
@stop

@section('assets')

  @parent
  
  <meta name="title" content="{{{ $news->title . ' - ' . trans('main.brand') }}}">
  <meta name="description" content="{{{ Helpers::shrtString($news->body, 200) }}}">
  <meta property="og:title" content="{{{ $news->title . ' - ' . trans('main.brand') }}}"/>
  <meta property="og:url" content="{{ Request::url() }}"/>
  <meta property="og:site_name" content="{{ trans('main.brand') }}"/>
  <meta property="og:image" content="{{ $news->image }}"/>
  <meta name="twitter:card" content="summary">
  <meta name="twitter:site" content="@{{ trans('main.brand') }}">
  <meta name="twitter:title" content="{{{ $news->title . ' - ' . trans('main.brand') }}}">
  <meta name="twitter:description" content="{{{ Helpers::shrtString($news->body, 200) }}}">
  <meta name="twitter:image" content="{{ $news->image }}">

@stop

@section('bodytag')
  <body id="news-show">
@stop

@section('content')

	<div class="container" id="content">
		<a class="btn btn-primary" href="/blog">&larr; Back to Blog</a>
		<br/>
		<br/>
		<article class="col-md-7">


			<img src="{{ $news->image }}" id="main-image">

			<div id="news-body">
				<h1>{{{ $news->title }}}</h1>

				<div class="byline clearfix">
					<span class="text-muted pull-left"><i class="fa fa-calendar"></i>	{{ $news->created_at }}</span>

					@if ($news->source)
						<div class="pull-right text-muted">{{ trans('main.source') }}: <a target="_blank" href="{{ $news->full_url }}">{{ $news->source }}</a></div>
					@endif
				</div>

				<p>{{ $news->body }}</p>

				<div id="disqus_thread"></div>
			</div>
		</article>
		<aside class="col-md-5 hidden-sm hidden-xs">
			<div class="heading"><i class="fa fa-fire"></i> {{ trans('main.recent news') }}</div>

			@if (isset($recent) && ! empty($recent))

				@foreach($recent as $k => $n)

				    @if ($k == 3)
						@if ($ad = $options->getHomeNewsAd())
				            <div id="ad">{{ $ad }}</div>
				        @endif
				    @endif

				    <div class="media">
						<a class="pull-left hidden-xs hidden-sm" href="{{{ Helpers::url($n->title, $n->id, 'news') }}}">
						    <img style="max-width:235px" class="media-object img-responsive" src="{{{ asset($n->image) }}}" alt="{{ 'Image of News Item' . $k }}">
						</a>
					  
					  	<div class="media-body clearfix">
							<a href="{{{ Helpers::url($n->title, $n->id, 'news') }}}">{{{ $n->title }}}</a> 
							<p class="visible-xs visible-sm">{{ Helpers::shrtString($n->body, 100) }}</p>

					     	<div class="text-muted">
					     		<span>{{ trans('main.from') }} {{{ $n->source ? $n->source : trans('main.brand') }}}</span>
					       		<span><i class="fa fa-clock-o"></i> {{ $n->created_at }}</span>
					     	</div>
					   </div>
					</div>

			  	  @endforeach

			@endif
		</aside>
	</div>
@stop

@section('scripts')
	<script>
		vars.disqus = '<?php echo $options->getDisqusShortname(); ?>';
		app.loadDisqus();
	</script>
@stop