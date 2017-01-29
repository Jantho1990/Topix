<!DOCTYPE html>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Topix Test 1</title>
  </head>
  <body>
    <main id="app">
      <div>@{{ testMessage }}</div>
      <div class="">
        <input type="text" v-model='randomTopicNumber'>
      </div>
      <div class="">
        <p>There are currently @{{topicsCount}} topics in the database.</p>
        <p>Here is a topic:</p>
        <p v-html="sampleTopic"></p>
      </div>
    </main>
    {{-- VueJS --}}
    <script type="text/javascript" src="https://unpkg.com/vue@2.1.10/dist/vue.js"></script>
    {{-- <script type="text/javascript" src="https://unpkg.com/vue-router@2.1.3/dist/vue-router.js"></script> --}}
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    <script src="https://unpkg.com/lodash@4.13.1/lodash.min.js"></script>
    <script type="text/javascript">
      var vm = new Vue({
        el: '#app',
        data: {
          title: 'Topix Test 1',
          testMessage: 'VueJS is working',
          rawTopicsCount: -1,
          randomTopicNumber: 1,
          rawSampleTopic: ''
        },
        computed: {
          topicsCount: function(){
            if(this.rawTopicsCount === -1){
              this.getTopicsCount();
            }
            return this.rawTopicsCount;
          },
          sampleTopic: function(){
            if(this.rawSampleTopic === ''){
              this.getSampleTopic(this.randomTopicNumber);
            }
            return this.rawSampleTopic;
          }
        },
        watch: {
          randomTopicNumber: _.debounce(function(id){
            if(this.randomTopicNumber > 0 && this.randomTopicNumber <= this.topicsCount){
              this.getSampleTopic(id);
            }else{
              this.rawSampleTopic = 'That\'s not a valid topic, dumbass.';
            }
          }, 500)
        },
        methods: {
          getTopicsCount: function(){
            var vue = this;
            axios.get('/api/topics/count')
              .then(function(response){
                vue.rawTopicsCount = response.data;
                console.log(response);
              })
              .catch(function(error){
                console.log(error);
              });
          },
          getSampleTopic: function(id){
            var vue = this;
            axios.get('/api/topics/' + id)
              .then(function(response){
                vue.rawSampleTopic = response.data;
                console.log(response);
              })
              .catch(function(error){
                console.log(error);
              })
          }
        }
      });
    </script>
  </body>
</html>
