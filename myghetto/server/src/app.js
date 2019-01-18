const PORT          = 8081;

const express       = require("express");
const bodyParser    = require("body-parser");
const cors          = require("cors");
const morgan        = require("morgan");

const app           = express();
    app.use(morgan('combine'));
    app.use(bodyParser.json());
    app.use(cors());

app.post("/register", function(req, res) {
    console.log("[REQUEST] register");
    res.send({
        message: "Auth successfull"
    })
});


app.listen(PORT);
