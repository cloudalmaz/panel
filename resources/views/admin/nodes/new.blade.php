@extends('layouts.admin')

@section('title')
    Nodes &rarr; New
@endsection

@section('content-header')
    <h1>Новая нода<small>Создание нового локального или удаленного нода для установки серверов.</small></h1>
    <ol class="breadcrumb">
        <li><a href="{{ route('admin.index') }}">Админ</a></li>
        <li><a href="{{ route('admin.nodes') }}">Ноды</a></li>
        <li class="active">Новый</li>
    </ol>
@endsection

@section('content')
<form action="{{ route('admin.nodes.new') }}" method="POST">
    <div class="row">
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Основные детали</h3>
                </div>
                <div class="box-body">
                    <div class="form-group">
                        <label for="pName" class="form-label">Название</label>
                        <input type="text" name="name" id="pName" class="form-control" value="{{ old('name') }}"/>
                        <p class="text-muted small">Ограничения по символам: <code>a-zA-Z0-9_.-</code> и <code>[Space]</code> (мин. 1, макс. 100 символов).</p>
                    </div>
                    <div class="form-group">
                        <label for="pDescription" class="form-label">Описание</label>
                        <textarea name="description" id="pDescription" rows="4" class="form-control">{{ old('description') }}</textarea>
                    </div>
                    <div class="form-group">
                        <label for="pLocationId" class="form-label">Локация</label>
                        <select name="location_id" id="pLocationId">
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}" {{ $location->id != old('location_id') ?: 'selected' }}>{{ $location->short }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Видимость ноды</label>
                        <div>
                            <div class="radio radio-success radio-inline">

                                <input type="radio" id="pPublicTrue" value="1" name="public" checked>
                                <label for="pPublicTrue"> Публичная </label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pPublicFalse" value="0" name="public">
                                <label for="pPublicFalse"> Приватная </label>
                            </div>
                        </div>
                        <p class="text-muted small">Установка <code>private</code> ноды запрещает автоматическое развертывание на ней.
                    </div>
                    <div class="form-group">
                        <label for="pFQDN" class="form-label">полное доменное имя</label>
                        <input type="text" name="fqdn" id="pFQDN" class="form-control" value="{{ old('fqdn') }}"/>
                        <p class="text-muted small">Пожалуйста, введите доменное имя (например, <code>node.example.com</code>) для использования для подключения к демону. IP-адрес может быть использован <em>только</em>, если вы не используете SSL для этой ноды.</p>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Общайтесь через SSL</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pSSLTrue" value="https" name="scheme" checked>
                                <label for="pSSLTrue"> Использовать SSL соединение</label>
                            </div>
                            <div class="radio radio-danger radio-inline">
                                <input type="radio" id="pSSLFalse" value="http" name="scheme" @if(request()->isSecure()) disabled @endif>
                                <label for="pSSLFalse"> Использовать HTTP соединение</label>
                            </div>
                        </div>
                        @if(request()->isSecure())
                            <p class="text-danger small">Ваша панель уже настроена для использования SSL соединения. Чтобы браузеры могли подключиться к вашей ноде, они <strong>должны</strong> использовать SSL соединение.</p>
                        @else
                            <p class="text-muted small">В большинстве случаев вы должны выбрать SSL соединение. Если вы используете IP-адрес или вы не хотите использовать SSL, выберите HTTP соединение.</p>
                        @endif
                    </div>
                    <div class="form-group">
                        <label class="form-label">Прокси</label>
                        <div>
                            <div class="radio radio-success radio-inline">
                                <input type="radio" id="pProxyFalse" value="0" name="behind_proxy" checked>
                                <label for="pProxyFalse"> Не за прокси </label>
                            </div>
                            <div class="radio radio-info radio-inline">
                                <input type="radio" id="pProxyTrue" value="1" name="behind_proxy">
                                <label for="pProxyTrue"> За прокси </label>
                            </div>
                        </div>
                        <p class="text-muted small">Если вы запускаете демон через прокси-сервер, например Cloudflare, выберите этот вариант, чтобы демон не искал сертификаты при загрузке.</p>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6">
            <div class="box box-primary">
                <div class="box-header with-border">
                    <h3 class="box-title">Конфигурация</h3>
                </div>
                <div class="box-body">
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonBase" class="form-label">Каталог файлов демон-сервера</label>
                            <input type="text" name="daemonBase" id="pDaemonBase" class="form-control" value="/var/lib/pterodactyl/volumes" />
                            <p class="text-muted small">Введите директорию, в которой будут храниться файлы сервера. <strong>Если вы используете OVH, проверьте схему разделов. Вам может понадобиться использовать <code>/home/daemon-data</code>, чтобы у вас было достаточно места.</strong></p>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemory" class="form-label">Общая память</label>
                            <div class="input-group">
                                <input type="text" name="memory" data-multiplicator="true" class="form-control" id="pMemory" value="{{ old('memory') }}"/>
                                <span class="input-group-addon">МиБ</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pMemoryOverallocate" class="form-label">Переполнение памяти</label>
                            <div class="input-group">
                                <input type="text" name="memory_overallocate" class="form-control" id="pMemoryOverallocate" value="{{ old('memory_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Введите общее количество памяти, доступного для новых серверов. Если вы хотите разрешить переполнение памяти, введите процент, который вы хотите разрешить. Чтобы отключить проверку на переполнение памяти введите <code>-1</code>. Ввод <code>0</code> предотвращает создание новых серверов, если оно будет привести к превышению лимита узла.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDisk" class="form-label">Общий объем диска</label>
                            <div class="input-group">
                                <input type="text" name="disk" data-multiplicator="true" class="form-control" id="pDisk" value="{{ old('disk') }}"/>
                                <span class="input-group-addon">МиБ</span>
                            </div>
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDiskOverallocate" class="form-label">Диск. Переполнение</label>
                            <div class="input-group">
                                <input type="text" name="disk_overallocate" class="form-control" id="pDiskOverallocate" value="{{ old('disk_overallocate') }}"/>
                                <span class="input-group-addon">%</span>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Введите общий объем диска для новых серверов. Если вы хотите разрешить переполнение диска, введите процент, который вы хотите разрешить. Чтобы отключить проверку переполнения диска введите <code>-1</code> в поле. Вводом <code>0</code> предотвращает создание новых серверов, если оно приведет к переполнению узла.</p>
                        </div>
                    </div>
                    <div class="row">
                        <div class="form-group col-md-6">
                            <label for="pDaemonListen" class="form-label">Порт демона</label>
                            <input type="text" name="daemonListen" class="form-control" id="pDaemonListen" value="8080" />
                        </div>
                        <div class="form-group col-md-6">
                            <label for="pDaemonSFTP" class="form-label">Порт SFTP демона</label>
                            <input type="text" name="daemonSFTP" class="form-control" id="pDaemonSFTP" value="2022" />
                        </div>
                        <div class="col-md-12">
                            <p class="text-muted small">Демон запускает свой SFTP-контейнер и не использует процесс SSHd на физическом сервере. <Strong>Не используйте тот же порт, что вы назначили для вашего физического сервера SSH.</strong> Если вы будете использовать демона за пределами CloudFlare&reg;, вы должны установить порт демона на <code>8443</code>, чтобы разрешить проксирование WebSocket через SSL.</p>
                        </div>
                    </div>
                </div>
                <div class="box-footer">
                    {!! csrf_field() !!}
                    <button type="submit" class="btn btn-success pull-right">Создать ноду</button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@section('footer-scripts')
    @parent
    <script>
        $('#pLocationId').select2();
    </script>
@endsection
