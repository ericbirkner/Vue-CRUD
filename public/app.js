/**
 * Vue.js with PHP Api
 * @author Gökhan Kaya <0x90kh4n@gmail.com>
 */

// Tüm post dataları json olarak gönderiliyor
// Form data olarak göndermek için true yapılır
Vue.http.options.emulateJSON = true;

var app = new Vue({
  el: '#app',
  data: {
	  id : '',
    name: '',
    surname: '',
    users: []
  },
  created: function() {
    this.fetchData(); // Başlangıçta kayıtları al
  },
  methods: {
    // Bu metot http get ile api üzerinden kayıtları users dizisine push eder
    fetchData: function() {

      this.$http.get('../api/api.php')
        .then(function(response) {

          if (response.body.status == 'ok') {

            let users = this.users;
            response.body.users.map(function(value, key) {
              users.push({id: value.id ,name: value.name, surname: value.surname});
            });

          }

        })
        .catch(function(error) {
          console.log(error);
        });

    },
    // Bu metot http post ile formdan alınan verileri apiye iletir
    // Apiden dönen cevap users dizisine push edilir
    onSubmit: function() {

      if (!this.name || !this.surname) {
        alert('ingresa los datos!');
        return false;
      }

      var postData = {method:'add',name: this.name, surname: this.surname};

      this.$http.post('../api/api.php', postData)
        .then(function(response) {

          if (response.body.status == 'ok') {
            this.users.push(response.body.users);
          }

          this.name = '';
          this.surname = '';

        })
        .catch(function(error) {
          console.log(error);
        });

    },
	myFunction : function(id, index){
		alert(id +"/"+index);
    //borro el elemento del array
    this.users.splice(this.users.indexOf(index), 1);

    var postData = {method:'delete',id: id};

    this.$http.post('../api/api.php', postData)
      .then(function(response) {
        console.log('se borro');
      })
      .catch(function(error) {
        console.log(error);
      });


	}
  }
})
