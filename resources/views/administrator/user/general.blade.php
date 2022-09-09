<input type="hidden" name="id" value="{{ $object->id }}">
<table class="table">
    <tr>
        <td>
            <label for="name">{{ __('user.columns.name') }}</label>
            <span class="text-danger">*</span>
        </td>
        <td>
            <input autofocus required id="name" name="name" type="text" class="form-control" value="{{ old('name') ?? $object->name }}">
            @error('name')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="email">{{ __('user.columns.email') }}</label>
            <span class="text-danger">*</span><br>
            <small class="text-secondary">{{ __('user.descriptions.email') }}</small>
        </td>
        <td>
            <input required id="email" name="email" type="email" class="form-control" value="{{ old('email') ?? $object->email }}">
            @error('email')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="new_password">{{ __('user.columns.new_password') }}</label>
        </td>
        <td>
            <input required id="new_password" name="new_password" type="password" class="form-control">
            @error('new_password')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="new_password_confirmation">{{ __('user.columns.new_password_confirmation') }}</label>
        </td>
        <td>
            <input required id="new_password_confirmation" name="new_password_confirmation" type="password" class="form-control">
            @error('new_password_confirmation')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label>{{ __('user.columns.avatar') }}</label>
        </td>
        <td>
            @include('administrator.partials.image-info', ['image' => $object->avatar ?? null])

            <input class="form-control" type="file" name="avatar" id="avatar" accept="image/*">
            @error('avatar')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

    <tr>
        <td>
            <label for="role">{{ __('user.columns.role') }}</label><br>
            <i class="fas fa-exclamation-triangle me-1 text-warning"></i>
            <small class="text-secondary">{{ __('user.descriptions.role') }}</small>
        </td>
        <td>
            <select name="role" id="role" class="form-select" @if ($object->role == \App\Models\User::ROLE_ADMIN && \Illuminate\Support\Facades\Auth::user()->created_at > $object->created_at) disabled @endif>
                <option @if (old('role') == \App\Models\User::ROLE_USER || $object->role == \App\Models\User::ROLE_USER) selected @endif value="{{ \App\Models\User::ROLE_USER }}">{{ __('user.roles')[\App\Models\User::ROLE_USER] }}</option>
                <option @if (old('role') == \App\Models\User::ROLE_ADMIN || $object->role == \App\Models\User::ROLE_ADMIN) selected @endif value="{{ \App\Models\User::ROLE_ADMIN }}">{{ __('user.roles')[\App\Models\User::ROLE_ADMIN] }}</option>
            </select>
            @error('role')<span class="text-danger">{{ $message }}</span>@enderror
        </td>
    </tr>

</table>
