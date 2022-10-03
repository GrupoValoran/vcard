@role(App\Models\Role::ROLE_SUPER_ADMIN)
<li class="nav-item {{ Request::is('sadmin/dashboard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('sadmin.dashboard') }}">
        <span class="aside-menu-icon pe-3"><i class="fa-solid fa-circle-dot"></i></span>
        <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/users*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('users.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-users"></i></span>
        <span class="aside-menu-title">{{ __('messages.vcard.user') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/vcard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('sadmin.vcards.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-id-card"></i></span>
        <span class="aside-menu-title">{{ __('messages.vcards') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/templates*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('sadmin.templates.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fa fa-address-card"></i></span>
        <span class="aside-menu-title">{{ __('messages.vcard.template') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/planSubscription*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('subscription.cash') }}">
        <span class="aside-menu-icon pe-3"><i class="fa fa-money-bill"></i></span>
        <span class="aside-menu-title">{{ __('messages.cash_payment') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/subscribedPlan*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('subscription.user.plan') }}">
        <span class="aside-menu-icon pe-3"><i class="fa fa-paper-plane"></i></span>
        <span class="aside-menu-title">{{ __('messages.subscribed_user') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/plans*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('plans.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-columns"></i></span>
        <span class="aside-menu-title">{{ __('messages.plans') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/currencies*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('currencies.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-dollar-sign"></i></span>
        <span class="aside-menu-title">{{ __('messages.currency.currencies') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/countries*', 'sadmin/states*', 'sadmin/cities*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('countries.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-globe-americas"></i></span>
        <span class="aside-menu-title">{{ __('messages.country.countries') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/languages*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('languages.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fa fa-language"></i></span>
        <span class="aside-menu-title">{{ __('messages.languages.languages') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/settings*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('setting.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-cogs"></i></span>
        <span class="aside-menu-title">{{ __('messages.settings') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('sadmin/front-cms*') || Request::is('sadmin/email-subscription*') || Request::is('sadmin/features*') ||
     Request::is('sadmin/about-us*') || Request::is('sadmin/frontTestimonial*') || Request::is('sadmin/contactUs*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('setting.front.cms') }}">
        <span class="aside-menu-icon pe-3"><i class="fa fa-home"></i></span>
        <span class="aside-menu-title">{{ __('messages.front_cms.front_cms') }}</span>
    </a>
</li>
@endrole



@role(App\Models\Role::ROLE_ADMIN)
<li class="nav-item {{ Request::is('admin/dashboard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('admin.dashboard') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-chart-pie"></i></span>
        <span class="aside-menu-title">{{ __('messages.dashboard') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('admin/vcard*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('vcards.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-id-card"></i></span>
        <span class="aside-menu-title">{{ __('messages.vcards') }}</span>
    </a>
</li>


<li class="nav-item {{ Request::is('admin/enquiries*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('enquiries.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-info-circle"></i></span>
        <span class="aside-menu-title">{{ __('messages.contact_us.contact_us') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('admin/appointments*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('appointments.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-calendar"></i></span>
        <span class="aside-menu-title">{{ __('messages.vcard.appointments') }}</span>
    </a>
</li>

<li class="nav-item {{ Request::is('admin/user-setting*') ? 'active' : '' }}">
    <a class="nav-link d-flex align-items-center py-3" aria-current="page" href="{{ route('user.setting.index') }}">
        <span class="aside-menu-icon pe-3"><i class="fas fa-cog"></i></span>
        <span class="aside-menu-title">{{ __('messages.settings') }}</span>
    </a>
</li>
@endrole





