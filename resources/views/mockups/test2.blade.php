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
    <script type="text/javascript" src="/js/test2.js"></script>
    <script type="text/javascript">
      var tjs = new topixJS();
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
          topicsCount: tjs.computeTopicsCount,
          sampleTopic: tjs.compueSampleTopic
        },
        watch: {
          randomTopicNumber: _.debounce(tjs.watchRandomTopicNumber, 500)
        },
        methods: {
          getTopicsCount: tjs.queryTopicsCount,
          getSampleTopic: function(id){
            return tjs.querySampleTopic(id);
          }
        }
      });
      //tjs.addVueInstance(vm);
    </script>
  </body>
</html>
