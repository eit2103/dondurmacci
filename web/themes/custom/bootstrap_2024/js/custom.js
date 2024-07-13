      document.addEventListener("DOMContentLoaded", function () {
          console.log("DOM fully loaded and parsed");
          var backToTopButton = document.getElementById("back-to-top");
          if (backToTopButton) {
              console.log("Back to Top button found:", backToTopButton);
              window.onscroll = function () {
                  console.log("Scroll event detected", document.body.scrollTop, document.documentElement.scrollTop);
                  if (document.body.scrollTop > 100 || document.documentElement.scrollTop > 100) {
                      console.log("Displaying button");
                      backToTopButton.style.display = "block";
                  } else {
                      console.log("Hiding button");
                      backToTopButton.style.display = "none";
                  }
              };
              backToTopButton.onclick = function () {
                  console.log("Back to Top button clicked");
                  window.scrollTo({ top: 0, behavior: 'smooth' });
              };
          } else {
              console.error("Back to Top button not found");
          }
      });