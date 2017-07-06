<template>
    <div class="row head-menu">
        <div class="col-md-12 text-center">
            <u>
                <router-link to="/">{{ captions['VIRTUAL_HOSTS'] }}</router-link>
            </u>
            <u>
                <router-link to="/mainconf/">{{ captions['MAIN_CONFIG'] }}</router-link>
            </u>
            <u>
                <router-link to="/setting/">{{ captions['SETTINGS'] }}</router-link>
            </u>
        </div>
    </div>
</template>
<script>

    export default{


        computed:{

            captions:function () {

                return this.$store.getters.captions;
            }
        }

    }

</script>