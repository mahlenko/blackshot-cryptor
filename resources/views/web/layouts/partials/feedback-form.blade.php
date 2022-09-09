<section class="order" id="order">
    <div class="wrap">
        <div class="order-box">
            <div class="order-box__left">
                <div class="order-box__block">
                    <img src="{{ asset('img/o1.jpg') }}" alt="" class="order-box__pic parralax-background adapt1">
                    <img src="{{ asset('img/o11.jpg') }}" alt="" class="order-box__pic parralax-background adapt2">
                    <div class="order-box__wrap1 parralax-front">
                        <span>Message</span>
                        <p>Successfully</p>
                        <svg class="order-box__icon icon"><use xlink:href="{{ asset('img/svg-sprite.svg#a1') }}"></use></svg>
                        <svg class="order-box__icon2 icon"><use xlink:href="{{ asset('img/svg-sprite.svg#check2') }}"></use></svg>
                    </div>
                    <div class="order-box__wrap2 parralax-front">
                        <img src="{{ asset('img/ava.png') }}" alt="">
                        <svg class="icon"><use xlink:href="{{ asset('img/svg-sprite.svg#a2') }}"></use></svg>
                    </div>
                </div>
                <div class="order-box__block2">
                    <img src="{{ asset('img/o2.jpg') }}" alt="" class="order-box__pic parralax-background">
                    <div class="order-box__wrap parralax-front">
                        <img src="{{ asset('img/ava2.png') }}" alt="">
                        <div class="order-box__content">
                            <p>Poll Meet</p>
                            <span>They answered my questions within 1 hour.</span>
                        </div>
                    </div>
                </div>
            </div>
            <div class="order-box__right">
                <div class="order-box__pos">
                    <p>{{ __('website.leave_a_request') }}</p>
                    <span>{{ __('website.leave_a_request_description') }}</span>
                </div>

                <form action="{{ route('feedback') }}" class="js-form order-form form" id="popupResult" method="post">
                    @csrf
                    <input type="hidden" name="url" value="{{ \Illuminate\Support\Facades\URL::current() }}">
                    <input type="text" name="name" required="" placeholder="{{ __('website.contacts.name') }}" class="form--input"/><br>
                    <input type="text" name="email" id="" required="" placeholder="{{ __('website.contacts.email') }}" class="form--input">
                    <textarea name="message" placeholder="{{ __('website.contacts.message') }}" class="form--textarea"></textarea>
                    <button class="btn order-form__btn" data-submit>{{ __('website.contacts.send') }}</button>
                </form>
            </div>
        </div>
    </div>
</section>
