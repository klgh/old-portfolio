var express = require('express');
var router = express.Router();

/* GET users listing. */
router.get('/', function(req, res, next) {
  console.log(req.session);
  res.send(req.session.user_id);
});
router.get('/logout', function(req, res, next) {
  req.session.destroy(function(err){
    if(err) res.send(err);
    res.send("Success");
  });

});
module.exports = router;
