<div class="bg-white border-b border-slate-200">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex gap-4 text-sm py-3">
        <a href="{{ route('admin.dashboard') }}" class="font-medium {{ request()->routeIs('admin.dashboard') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">Admin</a>
        <a href="{{ route('admin.users.index') }}" class="font-medium {{ request()->routeIs('admin.users.*') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">Users</a>
        <a href="{{ route('admin.case-studies.index') }}" class="font-medium {{ request()->routeIs('admin.case-studies.*') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">Case studies</a>
        <a href="{{ route('admin.plans.index') }}" class="font-medium {{ request()->routeIs('admin.plans.*') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">Plans</a>
        <a href="{{ route('admin.review-queue.index') }}" class="font-medium {{ request()->routeIs('admin.review-queue.*') ? 'text-slate-900' : 'text-slate-500 hover:text-slate-900' }}">Review queue</a>
    </div>
</div>
