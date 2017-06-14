var express = require('express');
var router = express.Router();

router.get('/callback/:code', function(req, res, next) {
  res.render('oauth2-callback');
});

router.post('/saveCredentials',function(req,res,next){
  req.session.token = req.body.token;
  req.session.user_id = req.body.user_id;
  req.session.save();
  return res.json({message:'save credential success'});
});

module.exports = router;
