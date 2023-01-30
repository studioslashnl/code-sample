<!-- see live at: studioslash.nl -->
<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<!-- header (with hero/menu) -->
<div>
    <div style="background-image: url('<?php echo esc_url( get_template_directory_uri() . '/assets/header-background.png'); ?>'); background-size: 70rem 57rem;" class="md:h-screen pt-6 md:pt-10 lg:pt-16 overflow-hidden bg-right-top bg-no-repeat header-image">
        <div class="container flex flex-col justify-between md:h-screen gap-10">
            <!-- menu -->
            <?php get_template_part( 'template-parts/layout/nav', 'content' ); ?>

            <!-- hero -->
            <div class="flex flex-col gap-8 md:gap-10">
                <h1 class="max-w-5xl text-heading">
                    <?php block_field( 'title' ); ?>
                </h1>

                <h2 class="max-w-[11rem] md:max-w-xl text-subheading">
                <?php block_field( 'sub-title' ); ?>
                </h2>
            </div>

            <!-- cta -->
            <?php if(trim(block_value('cta-text')) != ''): ?>
                <div class="z-10 md:z-0">
                    <a href="<?php block_field( 'cta-url' ); ?>"
                        class="button">
                        <?php block_field( 'cta-text' ); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            <?php endif; ?>

            <!-- hero image -->
            <div class="relative flex justify-end w-full -mt-[15.5rem] md:-mt-[12rem] lg:-mt-[41rem] pointer-events-none items-start">
                <img src="<?php echo esc_url( get_template_directory_uri() . '/assets/me.png'); ?>" alt="Joris W. van Rijn" class="max-w-[12rem] md:max-w-[27rem] lg:max-w-[35.5rem] z-0 md:z-10">   
                <?php
                if(trim(block_value('speech-bubble')) != ''){
                    ?>
                        <div style="transform: rotate(-3deg);">
                            <div class="speech-bubble text-lg p-5 absolute -ml-56 z-10 mt-10 font-display animate__tada animate__animated animate__delay-2s">
                                <?php block_field('speech-bubble'); ?>
                            </div>
                        </div>
                    <?php
                }
                ?>
            </div>
        </div>
    </div>

    <!-- line -->
    <div class="container">
        <hr />
    </div>

    <div class="md:hidden px-7 pt-10">
        <?php echo do_shortcode( '[grw id="610"]' ); ?>
    </div>
</div>