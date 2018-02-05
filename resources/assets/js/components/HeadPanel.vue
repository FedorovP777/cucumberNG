<template>
    <div>
        <div class="decorate-nav-bar-line">
            &nbsp;
        </div>
        <nav class="justify-content-center navbar navbar-expand-sm navbar-light nav-bar-custom">
            <ul class="nav navbar-nav col-sm-12 container p-3">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Nginx status: run</a>
                </li>
                <li class="nav-item col text-right">
                    <a class="nav-link" href="#">Info</a>
                </li>
            </ul>
        </nav>
    </div>
</template>
<script>
    export default {
        mounted() {
            console.log('Component mounted.')
        }
    }
</script>