<!-- ======== Modals ======== -->
<div data-modal="modalOrder" class="modal">
    <div class="modal--wrapper">
        <div class="modal--inner">
            <div class="modal--window">
                <div class="modal--close" data-modal-close>
                    <svg class="icon modal--closeicon">
                        <use xlink:href="{{ asset('img/svg-sprite.svg#closered') }}"></use>
                    </svg>
                </div>

                <div class="sec_heading sec_heading-modal">
                    <h2 class="sec_heading--h2">{{ __('website.leave_a_request') }}</h2>
                    <p class="sec_heading--subheading">{{ __('website.leave_a_request_description') }}</p>
                </div>

                <div class="modalform">
                    <form action="{{ route('feedback') }}" class="modalform--form" method="post">
                        @csrf
                        <input type="hidden" name="url" value="{{ \Illuminate\Support\Facades\URL::current() }}">
                        <input type="text" name="name" required="" placeholder="{{ __('website.contacts.name') }}" class="form--input">
                        <input type="text" name="email" required="" placeholder="{{ __('website.contacts.email') }}" class="form--input">
                        <textarea name="message" placeholder="{{ __('website.contacts.message') }}" class="form--textarea"></textarea>
                        <button class="btn" data-submit>{{ __('website.contacts.send') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
