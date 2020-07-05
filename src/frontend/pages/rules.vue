<template>
    <section>
        <b-container>
            <b-row style="min-height:48rem;" class="mt-5">
                <b-col>
                    <h2>{{content.name}}</h2>

                    <div v-html="content.description"/>
                </b-col>
            </b-row>
        </b-container>
    </section>
</template>

<script>
    import config from "../config";

    export default {
        async asyncData({$axios}) {
            let items = await $axios.$get(`${config.api_url}/page${location.pathname}?expand=_metaTags&_format=json`);
            const meta = items.data._metaTags;
            const content = items.data;
            return {meta, content}
        },
        data: () => ({
            meta: [],
            content: [],
        }),
        head() {
            const {title, description, keywords} = this.meta;
            return {
                title: title,
                meta: [
                    {
                        description: description,
                        keywords: keywords
                    } //локальные динамические метатеги
                ],
            };
        },
    }
</script>

<style scoped>

</style>
