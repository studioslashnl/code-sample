<!-- see live at: studioslash.nl -->
<!-- // Copyright: Joris W. van Rijn (2023). This code serves as a demonstrations of skills.
// This code may not be used or distributed for any purpose. -->

<?php
    $q = new WP_Query([ 'post_type' => 'testimonials', 'posts_per_page' => 99 ]); 

    $testimonials = [];

    // Construct testimonials array with nice WP functions
    if($q->have_posts()){
        while ( $q->have_posts() ) {
            $q->the_post(); 
            $testimonials[] = [
                'title' => get_the_title(),
                'content' => strip_tags(get_the_content()),
                'img' => get_the_post_thumbnail_url(),
            ];
        }
        wp_reset_postdata(); 
    }
?>

<script>
    const carousel = () => {
        return {
            selected: 0,
            testimonials: <?php echo json_encode($testimonials); ?>,
            enough: this.testimonials > 11,
            back(){
                if(this.selected - 1 < 0){
                    this.selected = this.testimonials.length - 1;
                }else{
                    this.selected = this.selected - 1;
                }
            },
            forward(){
                if(this.selected + 1 > this.testimonials.length - 1){
                    this.selected = 0;
                }else{
                    this.selected = this.selected + 1;
                }
            }
        };
    };
</script>

<!-- reviews -->
<div class="pb-40 bg-left-bottom bg-no-repeat -mb-20 reviews-slider"
    style="background-image: url('<?php echo is_front_page() ? esc_url( get_template_directory_uri() . '/assets/case-background.png') : ''; ?>'); background-size: 70rem; background-position: -10rem 10rem;" x-data="carousel()">
    <div class="container flex items-center gap-20">
        <div class="rotate-180 hover:scale-125 transition cursor-pointer hidden md:block" @click="back()">
            <svg width="50" height="18" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="stroke-current">
                <path d="M-2.40413e-07 6.5L16 6.5M16 6.5L10.4096 1M16 6.5L10.4096 12" />
            </svg>
        </div>

        <div class="lg:grid items-center lg:grid-cols-4 gap-10 text-center lg:text-left">
            <div>
                <img :src="testimonials[selected]['img']" class="rounded-full w-40 lg:w-full mx-auto" :alt="testimonials[selected]['title']">
            </div>

            <div class="flex flex-col col-span-3 gap-8 lg:h-72 justify-center">
                <h1 class="text-3xl font-display flex gap-3 items-center justify-center lg:justify-start mt-10 lg:mt-0">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" class="bi bi-chat-right-quote-fill" viewBox="0 0 16 16">
                        <path d="M16 2a2 2 0 0 0-2-2H2a2 2 0 0 0-2 2v8a2 2 0 0 0 2 2h9.586a1 1 0 0 1 .707.293l2.853 2.853a.5.5 0 0 0 .854-.353V2zM7.194 4.766c.087.124.163.26.227.401.428.948.393 2.377-.942 3.706a.446.446 0 0 1-.612.01.405.405 0 0 1-.011-.59c.419-.416.672-.831.809-1.22-.269.165-.588.26-.93.26C4.775 7.333 4 6.587 4 5.667 4 4.747 4.776 4 5.734 4c.271 0 .528.06.756.166l.008.004c.169.07.327.182.469.324.085.083.161.174.227.272zM11 7.073c-.269.165-.588.26-.93.26-.958 0-1.735-.746-1.735-1.666 0-.92.777-1.667 1.734-1.667.271 0 .528.06.756.166l.008.004c.17.07.327.182.469.324.085.083.161.174.227.272.087.124.164.26.228.401.428.948.392 2.377-.942 3.706a.446.446 0 0 1-.613.01.405.405 0 0 1-.011-.59c.42-.416.672-.831.81-1.22z"/>
                    </svg>
                    <span x-html="testimonials[selected]['title']"></span>
                </h1>

                <p class="leading-relaxed font-body text-sm md:text-base lg:text-lg">
                    "<span x-text="testimonials[selected]['content']"></span>"
                </p>

                <div class="flex gap-5 justify-center lg:justify-start">
                    <div class="flex -space-x-2 overflow-hidden isolate pl-2">
                        <template x-for="i in 4">
                            <img :src="testimonials[i]['img']" :alt="testimonials[i]['title']" class="relative inline-block w-10 h-10 rounded-full ring-2 ring-white">
                        </template>
                    </div>

                    <a href="https://studioslash.nl/testimonials/"
                        class="flex items-center gap-3 text-sm font-semibold text-royal-blue-500 font-display hover:underline">
                        <span class="hidden md:block">
                            Lees de reviews van <span x-text="testimonials.length" x-show="enough">10</span> anderen
                        </span>
                        <span class="md:hidden">
                            Meer reviews
                        </span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5L21 12m0 0l-7.5 7.5M21 12H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </div>

        <div class="rotate-0 hover:scale-125 transition cursor-pointer hidden md:block" @click="forward()">
            <svg width="50" height="18" viewBox="0 0 17 13" fill="none" xmlns="http://www.w3.org/2000/svg"
                class="stroke-current">
                <path d="M-2.40413e-07 6.5L16 6.5M16 6.5L10.4096 1M16 6.5L10.4096 12" />
            </svg>
        </div>
    </div>
</div>