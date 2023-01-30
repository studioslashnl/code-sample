<!-- see live at: studioslash.nl -->
<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<!-- cta -->
<div class="w-full p-10 md:p-20 text-white bg-purple-500 bg-center bg-no-repeat bg-cover"
    style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/case-background.png'); ?>'); ">
    <div class="container flex flex-col max-w-4xl gap-10 md:gap-20 p-0">
        <!-- cta: canvas -->
        <div class="md:grid items-center md:grid-cols-2 md:gap-20">
            <div class="flex flex-col gap-5">
                <h1 class="text-2xl font-display"><?php block_field( 'cta-title-1' ); ?></h1>
                <p class="font-body text-sm md:text-base">
                    <?php block_field( 'cta-desc-1' ); ?>
                </p>
                <a href="<?php block_field( 'cta-link-location-1' ); ?>"
                    class="flex items-center gap-3 md:mt-6 text-sm font-semibold text-white font-display hover:underline">
                    <?php block_field( 'cta-link-text-1' ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
            <div class="flex justify-center mt-5 md:mt-0">
                <div>
                    <a href="https://studioslash.nl/websitecanvas" class="flex items-center md:-space-x-52 isolate">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/canvas.png'); ?>" alt="Websitecanvas"
                            class="relative w-60 rounded shadow-2xl hidden md:block">
                        <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/canvas.png'); ?>" alt="Websitecanvas"
                            class="relative rounded shadow-2xl w-72">
                    </a>
                </div>
            </div>
        </div>
        <!-- cta: calculator -->
        <div class="grid items-center md:grid-cols-2 md:gap-20 p-0 gap-5 max-w-full">
            <div class="flex justify-center order-2 md:order-1">
                <div
                    class="flex flex-col items-center gap-10 p-5 md:pt-10 ext-center bg-white shadow-2xl rounded-2xl">
                    <h1 class="text-purple-500 font-display">VRAAG 1</h1>

                    <div class="flex flex-col gap-3 costs-calculator">
                        <h1 class="title">Hoeveel pagina's heeft de website?</h1>
                        <span class="option-group !shadow-none">
                            <a href="https://studioslash.nl/kosten/?a=1-5" class="option w-1/4 flex justify-center">1-5</a>
                            <a href="https://studioslash.nl/kosten/?a=5-10" class="option w-1/4 flex justify-center">5-10</a>
                            <a href="https://studioslash.nl/kosten/?a=10-20" class="option w-1/4 flex justify-center">10-20</a>
                            <a href="https://studioslash.nl/kosten/?a=20" class="option w-1/4 flex justify-center">20+</a>
                        </span>
                    </div>

                    <a href="https://studioslash.nl/kosten"
                        class="flex items-center gap-3 text-sm font-semibold text-purple-500 font-display hover:underline">
                        Volgende vraag
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
            <div class="flex flex-col gap-5 order-1 md:order-2">
                <h1 class="text-2xl font-display"><?php block_field( 'cta-title-2' ); ?></h1>
                <p class="font-body text-sm md:text-base">
                    <?php block_field( 'cta-desc-2' ); ?>
                </p>
                <a href="<?php block_field( 'cta-link-location-2' ); ?>"
                    class="flex items-center gap-3 md:mt-6 text-sm font-semibold text-white font-display hover:underline">
                    <?php block_field( 'cta-link-text-2' ); ?>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                    </svg>
                </a>
            </div>
        </div>
    </div>
</div>