var topixJS = function(vueInstance){

  var my = this;

  // var vm;
  // if(typeof vueInstance !== 'undefined'){
  //   vm = vueInstance;
  // }

  /**
   *  Pass in a vue instance.
   *  @return void
   */
  // my.addVueInstance = function(vueInstance){
  //   vm = vueInstance;
  // };

  /**
   *  Get the computed value topicsCount.
   *  @return Integer
   */
  my.computeTopicsCount = function(){
    if(vm.rawTopicsCount === -1){
      vm.getTopicsCount();
    }
    return vm.rawTopicsCount;
  };

  /**
   *  Get the computed value of sampleTopic.
   *  @return string
   */
  my.computeSampleTopic = function(){
    if(vm.rawSampleTopic === ''){
      vm.getSampleTopic(vm.randomTopicNumber);
    }
    return vm.rawSampleTopic;
  };

  /**
   *  Watch function to check if the topic id input has changed.
   *  @param id The topic id.
   *  @return void
   */
  my.watchRandomTopicNumber = function(){
    if(vm.randomTopicNumber > 0 && vm.randomTopicNumber <= this.topicsCount){
      vm.getSampleTopic(id);
    }else{
      vm.rawSampleTopic = 'That\'s not a valid topic, dumbass.';
    }
  };

  /**
   *  Query the server to get the total number of topics.
   *  @return void
   */
  my.queryTopicsCount = function(){
    axios.get('/api/topics/count')
      .then(function(response){
        vm.rawTopicsCount = response.data;
        console.log(response);
      })
      .catch(function(error){
        console.log(error);
      });
  };

  /**
   *  Query the server to get the sample topic.
   *  @param id The id of the topic.
   *  @return void
   */
  my.querySampleTopic = function(){
    axios.get('/api/topics/' + id)
      .then(function(response){
        vm.rawSampleTopic = response.data;
        console.log(response);
      })
      .catch(function(error){
        console.log(error);
      })
  };

};
