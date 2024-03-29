@extends('Main.Boilerplate')

@section('title')
	<title> {{  trans('main.create new title') . ' - ' . trans('main.brand')}} </title>
@stop

@section('bodytag')
	<body id="create-edit-page">
@stop

@section('content')
    <div class="container" id="content">

        <section class="col-sm-9">
            <ul class="nav nav-tabs nav-justified">
                <li class="active"><a href="#details" data-toggle="tab">{{ trans('main.details') }}</a></li>
                <li><a href="#images" data-toggle="tab">{{ trans('dash.images') }}</a></li>
                <li><a href="#cast" data-toggle="tab">{{ trans('main.cast') }}</a></li>
                
                @if(isset($title))
                    <li><a href="#links" data-toggle="tab">{{ trans('stream.links') }}</a></li>
                    <li><a href="#seasons" data-toggle="tab">{{ trans('main.seasons') }}</a></li>
                @endif
            </ul>

            <div class="tab-content">
                <div class="tab-pane active" id="details">
                    {{ Form::open(array('route' => array(Str::slug(trans('main.movies')) . '.store'), 'id' => 'main-form')) }}
                        @include('Titles.Partials.CreateEditForm')
                    {{ Form::close() }}
                </div>

                <div class="tab-pane" id="images">
                    <div class="clearfix" data-bind="foreach: app.models.title.images">
                        <figure class="col-sm-4 col-xs-6">
                            <img data-bind="attr: { src: path }" class="img-responsive img-thumbnail">
                            <div class="delete-icon" data-bind="click: app.viewModels.titles.create.removeImage"><i class="fa fa-times"></i></div>
                        </figure>
                    </div>
                </div>

                @if(isset($title))
                    <div class="tab-pane" id="links">
                       <div class="clearfix">
                            
                            <div class="form-group">
                                <label for="newLink">{{ trans('dash.url') }}</label>
                                <input type="text" name="newLink" placeholder="Url..." class="form-control" data-bind="value: app.models.link.url, valueUpdate: 'keyup'">
                            </div>

                            <div class="form-group">
                                <label for="link-label">{{ trans('stream.label') }}</label>
                            <input type="text" class="form-control" name="link-label" data-bind="value: app.models.link.label, valueUpdate: 'keyup'">
                            </div>

                            <div class="form-group">
                                <label for="link-type">{{ trans('main.type') }}</label>
                                <select name="link-type" class="form-control" data-bind="value: app.models.link.type">
                                    <option value="embed">{{ trans('stream.embed') }}</option>
                                    <option value="video">{{ trans('stream.videojs') }}</option>
                                    <option value="external">{{ trans('stream.openExternal') }}</option>
                                </select>
                            </div>

                            <!-- ko if: app.models.title.type == 'series' -->
                            <div class="form-group">
                                <label for="link-season">{{ trans('stream.seasonNum') }}</label>
                                <select name="link-season" class="form-control" data-bind="value: app.models.link.season, foreach: app.models.title.seasons()">
                                    <option data-bind="attr: { value: $index()+1 }, text: $index()+1"></option> 
                                </select>
                            </div>
                            <!-- /ko -->

                            <!-- ko if: app.models.link.season -->
                            <div class="form-group">
                                <label for="link-episode">{{ trans('stream.episodeNum') }}</label>
                                <select name="link-episode" class="form-control" data-bind="value: app.models.link.episode, foreach: app.models.title.seasons()[app.models.link.season()-1].episode">
                                    <option data-bind="attr: { value: $index()+1 }, text: $index()+1"></option> 
                                </select>
                            </div>
                            <!-- /ko -->

                            <button class="btn btn-primary" data-bind="click: addLink, enable: app.models.link.url">{{ trans('main.attach') }}</button>
                       </div>
                       <br>
                       <hr>
                       <br>

                        <!-- ko if: app.models.title.links().length > 0 -->
                        <table class="table table-striped table-centered table-bordered">
                            <thead>
                                <tr>
                                    <th>{{ trans('stream.label') }}</th>

                                    <!-- ko if: app.models.title.type == 'series' -->
                                    <th>{{ trans('stream.seasonNum') }}</th>
                                    <th>{{ trans('stream.episodeNum') }}</th>
                                    <!-- /ko -->

                                    <th>{{ trans('main.type') }}</th>
                                    <th>{{ trans('stream.reports') }}</th>
                                    <th>{{ ucfirst(trans('dash.url')) }}</th>
                                    <th>{{ trans('dash.actions') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: app.models.title.links">
                                <tr>
                                    <td class="col-sm-2" data-bind="text: label"></td>

                                    <!-- ko if: app.models.title.type == 'series' -->
                                    <td class="col-sm-1" data-bind="text: season"></td>
                                    <td class="col-sm-1" data-bind="text: episode"></td>
                                    <!-- /ko -->

                                    <td class="col-sm-2" data-bind="text: type"></td>
                                    <td class="col-sm-1" data-bind="text: reports"></td>
                                    <td class="col-sm-3" data-bind="text: url"></td>
                                    <td class="col-sm-2">
                                        <button type="button" class="btn-sm btn-danger btn" data-bind="click: $root.removeLink"><i class="fa fa-trash-o"></i> </button>
                                        <button type="button" class="btn-sm btn-warning btn" data-bind="click: $root.editLink"><i class="fa fa-wrench"></i> </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /ko -->
                    </div>

                    <div class="tab-pane clearfix" id="seasons">
                        <section class="clearfix season-btns">
                            <ul class="pull-left list-unstyled" data-bind="foreach: app.models.title.seasons">
                                <button class="btn btn-primary" data-bind="text: number, click: $root.setActiveSeason"></button>
                            </ul>

                            <button class="btn btn-danger pull-right" data-bind="click: deleteSeason, enable: activeSeason()">{{ trans('dash.deleteSeason') }}</button>
                            <button class="btn btn-primary pull-right" data-bind="click: addSeason">{{ trans('dash.new season') }}</button>
                            <button class="btn btn-primary pull-right" data-bind="click: showEpisodeModal, enable: activeSeason()">{{ trans('dash.newEpisode') }}</button>
                        </section>

                        <!-- ko if: activeSeason() && activeSeason().episode.length > 0 -->
                        <table class="table table-striped table-centered">
                            <thead>
                                <tr>
                                    <th>{{ trans('main.poster') }}</th>
                                    <th>{{ trans('main.title') }}</th>
                                    <th>{{ trans('main.plot') }}</th>
                                    <th>{{ trans('main.number') }}</th>
                                    <th>{{ trans('dash.actions') }}</th>
                                    
                                </tr>
                            </thead>
                            <tbody data-bind="foreach: activeSeason().episode">
                                <tr>
                                    <td class="col-sm-2"><img class="img-responsive col-sm-12" data-bind="attr: { src: poster, alt: title }"></td>
                                    <td class="col-sm-3" data-bind="text: title"></td>
                                    <td class="col-sm-4" data-bind="text: plot ? plot.trunc(100) : null"></td>
                                    <td class="col-sm-1"  data-bind="text: episode_number"></td>
                                    <td class="col-sm-2">
                                        <button type="button" class="btn-sm btn-danger btn" data-bind="click: $root.removeEpisode"><i class="fa fa-trash-o"></i> </button>
                                        <button type="button" class="btn-sm btn-warning btn" data-bind="click: $root.editEpisode"><i class="fa fa-wrench"></i> </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                        <!-- /ko -->

                        <!-- ko if: activeSeason() && activeSeason().episode.length == 0 -->
                        <h3 align="center">{{ trans('dash.noEpisodes') }}</h3>
                        <!-- /ko -->

                    </div>
                @endif

                <div class="tab-pane" id="cast">
                    
                    <label for="cast-query">Search for an actor...</label>
                    <input type="text" id="cast-query" class="form-control" autocomplete="off" data-bind="value: castQuery, valueUpdate: 'keyup'">

                    <div class="autocomplete-container" data-bind="visible: castResults().length > 0, hideOnBlur">
                        <div class="arrow-up"></div>
                        <section class="auto-heading">{{ trans('dash.resultsFor') }} <span data-bind="text: castQuery"></span></section>

                        <section class="suggestions" data-bind="foreach: castResults">
                            <div class="media" data-bind="click: $root.addActor">
                                <a href="#" class="col-sm-1">
                                    <img class="media-object img-responsive" data-bind="attr: { src: image, alt: title }">
                                </a>
                                <div class="media-body">
                                    <h6 class="media-heading" data-bind="text: name"></h6>
                                    <p data-bind="text: bio ? bio.trunc(250) : null"></p>
                                </div>
                            </div>
                        </section>
                    </div>

                    <div class="clearfix" id="cast-cont" data-bind="foreach: app.models.title.actors">
                        <figure class="col-sm-6 col-md-3">
                            <img data-bind="attr: { src: image.indexOf('http') > -1 ? image : vars.urls.baseUrl + '/' + image }" class="img-responsive">
                            <figcaption class="clearfix">
                                <div class="pull-left" data-bind="text: name"></div>
                                <div class="pull-right" data-bind="click: app.viewModels.titles.create.removeActor"><i class="fa fa-times"></i></div>
                            </figcaption>
                            <input type="text" class="form-control" data-bind="value: app.models.title.actors()[$index()].char_name, valueUpdate: 'keyup'" placeholder="Character name...">
                        </figure>
                    </div>

                </div>
            </div>  
        </section>

        <section class="col-sm-3">
            <div class="panel panel-default" data-bind="preventSubmitOnEnter">
                <div class="panel-heading"><i class="fa fa-save"></i> {{ trans('dash.save') }}</div>
                <div class="panel-body">
                    <button type="button" class="btn btn-primary" data-bind="click: save">
                        {{ trans('dash.save') }}
                    </button>
                </div>  
            </div>

            <div class="panel panel-default" data-bind="preventSubmitOnEnter">
                <div class="panel-heading"><i class="fa fa-picture-o"></i> {{ trans('dash.uploadImage') }}</div>
                <div class="panel-body">
                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#upload-media-modal">
                        {{ trans('dash.uploadImage') }}
                    </button>
                </div>  
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-bullhorn"></i> {{ trans('dash.attachDirectors') }}</div>
                <div class="panel-body">
                    <input type="text" data-bind="value: newDirector, preventSubmitOnEnter, valueUpdate: 'keyup'" class="form-control" placeholder="Full Name...">

                    <ul class="list-unstyled list-inline" data-bind="foreach: app.models.title.directors">
                        <li data-bind="click: $root.removeDirector"><i class="fa fa-times"></i> <span data-bind="text: $data"></span></li>
                    </ul>

                    <button class="btn btn-primary" data-bind="click: addDirector">{{ trans('main.add') }}</button>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading"><i class="fa fa-pencil"></i> {{ trans('dash.attachWriters') }}</div>
                <div class="panel-body">
                    <input type="text" data-bind="value: newWriter, preventSubmitOnEnter, valueUpdate: 'keyup'" class="form-control" placeholder="Full Name...">

                    <ul class="list-unstyled list-inline" data-bind="foreach: app.models.title.writers">
                        <li data-bind="click: $root.removeWriter"><i class="fa fa-times"></i> <span data-bind="text: $data"></span></li>
                    </ul>

                    <button class="btn btn-primary" data-bind="click: addWriter">{{ trans('main.add') }}</button>
                </div>
            </div>
        </section>

        @if (isset($title))
            @include('Titles.Partials.EditEpisodeModal')
        @endif
    </div>

    @include('Partials.MediaUploadModal')
    
@stop

@section('scripts')

    {{ HTML::script('assets/js/vendor/uploader.min.js') }}

    @if (isset($title))
        <script>
            vars.title = <?php echo $title->toJson(); ?>;
            app.viewModels.titles.create.map();
        </script>
    @endif

    <script>
        app.viewModels.titles.create.start();
    </script>

@stop