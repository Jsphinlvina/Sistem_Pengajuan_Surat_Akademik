<div x-data="{ pageName: `Basic Tables`}">
    <include src="./partials/breadcrumb.html" />
</div>

<div class="space-y-5 sm:space-y-6">
    <div class="rounded-2xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
        <div class="px-5 py-4 sm:px-6 sm:py-5">
            <h3 class="text-base font-medium text-gray-800 dark:text-white/90">
                {{ $title }}
            </h3>
        </div>
        <div class="p-5 border-t border-gray-100 dark:border-gray-800 sm:p-6">
            <!-- ====== Table Six Start -->
            <div
                class="overflow-hidden rounded-xl border border-gray-200 bg-white dark:border-gray-800 dark:bg-white/[0.03]">
                <div class="max-w-full overflow-x-auto">
                    <table class="min-w-full">
                        <!-- table header start -->
                        <thead>
                            <tr class="border-b border-gray-100 dark:border-gray-800">
                                {{ $head }}
                            </tr>
                        </thead>
                        <!-- table header end -->
                        <!-- table body start -->
                        <tbody class="divide-y divide-gray-100 dark:divide-gray-800">
                            {{ $slot }}
                        </tbody>
                    </table>
                </div>
            </div>
            <!-- ====== Table Six End -->
        </div>
    </div>
</div>
