{{-- This file is used for menu items by any Backpack v7 theme --}}
<li class="nav-item"><a class="nav-link" href="{{ backpack_url('dashboard') }}"><i class="la la-home nav-icon"></i> {{ trans('backpack::base.dashboard') }}</a></li>

<x-backpack::menu-item title="Users" icon="la la-question" :link="backpack_url('user')" />
<x-backpack::menu-item title="Shops" icon="la la-question" :link="backpack_url('shop')" />
<x-backpack::menu-item title="Products" icon="la la-question" :link="backpack_url('product')" />
<x-backpack::menu-item title="Posts" icon="la la-question" :link="backpack_url('post')" />
<x-backpack::menu-item title="Orders" icon="la la-question" :link="backpack_url('order')" />
<x-backpack::menu-item title="Order approvals" icon="la la-question" :link="backpack_url('order-approval')" />