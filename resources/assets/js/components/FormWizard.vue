
<template>
    <form-wizard >
        <tab-content v-for="tab in tabs"
                    v-if="!tab.hide"
                    :key="tab.title"
                    :title="tab.title"
                    :icon="tab.icon">
            <component :is="tab.component"></component>
        </tab-content>
    </form-wizard>
</template>


<script>
  import VueFormWizard from 'vue-form-wizard';
Vue.component('step1', {
    template:` <div> My first tab content <br>
     		 </div>`
 }
)
Vue.component('step2', {
    template:`<div>  My second tab content </div>`
})
Vue.component('step3', {
    template:`<div>  My third tab content </div>`
})
Vue.component('step4', {
    template:`<div> Yuhuuu! This seems pretty damn simple </div>`
})
export default {
    data() {
        return {
            tabs: 
            [
                {title: 'Personal details', icon: 'ti-user', component: 'step1'}, 
                {title: 'Is Logged In?', icon: 'ti-settings', component: 'step2', hide: false},
                {title: 'Additional Info', icon: 'ti-location-pin', component: 'step3'},
                {title: 'Last step', icon: 'ti-check', component: 'step4'},
            ],
        }
    }
}
</script>
