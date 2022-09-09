<div class="card">
    <div class="card-header">
        Зарегистрированные пользователи
    </div>
    <ol class="list-group list-group-flush">
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <span>Администраторов</span>
            </div>
            <small>{{ $administrators }}</small>
        </li>
        <li class="list-group-item d-flex justify-content-between align-items-start">
            <div class="ms-2 me-auto">
                <a href="{{ route('admin.user.home') }}">Всего</a>
            </div>
            <small>{{ $users }}</small>
        </li>
    </ol>
</div>
