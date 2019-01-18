<?php include "inc/current_site.php"; ?>


        <section class="container">
            <div class="row title">
                <div class="small-12 column">
                    <h6>How to reach us</h6>
                </div>
            </div>


            <div class="row">
                <div class="large-7 column">
                    <div class="result-success bounceIn animated hide top-spacer text-center">
                        <i class="fa fa-thumbs-up text-green text-size--xxl"></i>

                        <h4 class="text-green">
                        Your email has been sent.
                    </h4>
                    </div>

                    <div class="result-error shake animated hide top-spacer text-center">
                        <i class="fa fa-exclamation-triangle text-red text-size--xxl"></i>

                        <h4 class="text-red">
                        There was a problem sending this message. Please contact us by email.
                    </h4>
                    </div>

                    <form action="sendmail.php" method="POST" data-abide="ajax">
                        <p>
                            If you're having trouble, the first place to look for help is on our <a href="support.html">support</a> page. If you still need our help, please fill the form below:
                        </p>

                        <div class="row">
                            <div class="large-8 column">
                                <label for="name">Name:</label>
                                <input id="name" name="name" type="text" required>
                                <small class="error">Name is required.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-8 column">
                                <label for="email">Email:</label>
                                <input id="email" name="email" type="email" required>
                                <small class="error">Email is required and must be valid.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 column">
                                <label for="subject">Subject:</label>
                                <input id="subject" name="subject" type="text" required>
                                <small class="error">Subject is required.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 column">
                                <label>Message:</label>
                                <textarea id="message" name="message" required></textarea>
                                <small class="error">Message is required.</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 column">
                                <button class="secondary">Send Message<i class="fa fa-paper-plane left-spacer--xs"></i>
                                </button>
                            </div>
                        </div>

                        <div class="row">
                            <div class="large-12 column">
                                <small>
                                <em>All fields are required.</em>
                            </small>
                            </div>
                        </div>
                    </form>

                    <div class="spinner-wrap hide text-center">
                        <h4>Please wait...</h4>

                        <div class="spinner bounceIn animated">
                            <div class="rect1"></div>
                            <div class="rect2"></div>
                            <div class="rect3"></div>
                            <div class="rect4"></div>
                            <div class="rect5"></div>
                        </div>
                    </div>
                </div>

                <div class="large-4 column">
                    <div id="map-canvas"></div>
                    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&amp;sensor=false"></script>
                    <script>
                        var latitude = -37.817379;
                        var longitude = 144.955477;
                        var title = 'Hello World';

                        function initialize() {
                            var e = new google.maps.LatLng(latitude, longitude);
                            var t = {
                                zoom: 16,
                                center: e,
                                mapTypeId: google.maps.MapTypeId.ROADMAP
                            };
                            var n = new google.maps.Map(document.getElementById("map-canvas"), t);
                            var r = new google.maps.Marker({
                                position: e,
                                map: n,
                                title: title
                            })
                        }
                        google.maps.event.addDomListener(window, "load", initialize)
                    </script>

                    <div class="row">
                        <div class="large-3 small-2 column">
                            <h6>Address:</h6>
                        </div>

                        <div class="large-9 small-10 column">
                            <p>121 King Street, Melbourne Victoria 3000 Australia</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-3 small-2 column">
                            <h6>Phone:</h6>
                        </div>

                        <div class="large-9 small-10 column">
                            <p>+61 3 8376 6284
                                <br>+61 3 8376 6287</p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="large-3 small-2 column">
                            <h6>Email:</h6>
                        </div>

                        <div class="large-9 small-10 column">
                            <p>
                                <a href="mailto:info@filoxenia.com">info@filoxenia.com</a>
                                <br>
                                <a href="mailto:info@filoxenia.com">support@filoxenia.com</a>
                            </p>
                        </div>
                    </div>

                </div>
            </div>
        </section>