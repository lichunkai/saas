<template>
	<Carousel :height="200" v-model="value" :autoplay="setting.autoplay" :autoplay-speed="setting.autoplaySpeed" :dots="setting.dots" :radius-dot="setting.radiusDot" :trigger="setting.trigger" :arrow="setting.arrow">
		<CarouselItem v-for="(item, index) in news">
			<div>
                <img :src="item.news_images" @click="jumpUrl(item.news_id)">
			</div>
		</CarouselItem>

	</Carousel>
</template>

<script>
	export default {
		name: 'myCarousel',
		data() {
			return {
				value: 0,
				setting: {
					autoplay: true,
					autoplaySpeed: 5000,
					dots: 'inside',
					radiusDot: false,
					trigger: 'click',
					arrow: 'hover',
				}
			}
		},
        props: ['news'],
        methods: {
            jumpUrl(news_id){
                let argu = { order_id: news_id };
                this.$router.push({
                    name: 'order-info',
                    params: argu
                });
            }
        }

	}
</script>

<style scoped>
	/*轮播*/
	img {
		width: 100%;
		min-height: 200px;
	}
</style>