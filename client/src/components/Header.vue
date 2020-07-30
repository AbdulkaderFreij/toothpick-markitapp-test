<template>
    <v-toolbar fixed class="green" dark>
        <v-toolbar-title class="mr-4">
            <span class="home" @click="navigateTo({name:'home'})">Posty</span>
        </v-toolbar-title>
        <!--
        <v-toolbar-items>
            <v-btn class="green" flat dark>
                Posts
            </v-btn>
        </v-toolbar-items>
-->
    <v-spacer></v-spacer>
            <v-toolbar-items>
            <v-btn flat dark id="login-home" @click="navigateTo({name:'login'})">
                Login
            </v-btn>
        </v-toolbar-items>
    </v-toolbar>
</template>

<script>
export default {
    methods: {
        navigateTo(route){
        this.$router.push(route)
        }
    }
}
</script>

<style scoped>

.home{
cursor:pointer;
}

.home:hover , #login-home:hover{
color: gold;
}

#login-home{
background-color:#4caf50;
border:none;
outline:none;
}

.v-btn--contained{
box-shadow:unset;
}
    
</style>