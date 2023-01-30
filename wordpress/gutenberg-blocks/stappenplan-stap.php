<!-- see live at: studioslash.nl/stappenplan -->
<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<div class="flex flex-col md:grid md:grid-cols-2 gap-2 md:items-center group mb-10 md:mb-0">
    <div class="flex gap-5 items-center">
        <div class="w-20">
            <span class="flex h-20 w-20 items-center justify-center rounded-full border-[4px] border-purple-500 group-hover:bg-purple-500 transition group-hover:scale-110 relative">
                <span class="text-purple-600 text-3xl group-hover:text-white"><?php block_field('number'); ?>.</span>
                <?php if(block_value('free')): ?>
                    <span class="absolute block ml-5 mt-16 px-2 py-1 bg-purple-500 text-white rounded-2xl" style="transform: rotate(-3deg);">Gratis</span>
                <?php endif; ?>
            </span>
        </div>
        <div>
            <h1 class="text-2xl !mt-0 font-display"><?php block_field('title'); ?></h1>
            <p class="text-slate-700 !mb-0 font-display"><?php block_field('sub-title'); ?></p>
        </div>
    </div>
    <div class="text-sm font-light text-slate-700">
        <p class="!mb-0 leading-relaxed"><?php block_field('description'); ?></p>
    </div>
</div>