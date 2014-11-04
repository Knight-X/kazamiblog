<!DOCTYPE>
<html>
  <head>
    <meta charset="utf-8" />
    <script type="text/javascript">
       function log(str) {

          var log_node = document.getElementById("log");
          var text_node = document.createTextNode(str);
          var br_node = document.createElement("br");
          log_node.appendChild(text_node);
          log_node.appendChild(br_node);
       }
    </script>
     <script type="text/javascript">
       function Reader(str){
         this.data = str;
         this.currPos = 0;
         this.length = str.length;
       }

       Reader.prototype.nextChar = function(){
         if (this.currPos >= this.length){
           return -1;
         }

         return this.data[this.currPos++];
       }

       Reader.prototype.retract = function(n) {
         if (n == undefined){
           n = 1;
         }
         this.currPos -= n;
         if (this.currPos < 0){
           this.currPos = 0;
         }
       }
     </script>


     <script type="text/javascript">
       window.onload = function() {
         var textarea = document.getElementById("source_code");
         var run_button = document.getElementById("run");

         run_button.onclick = function() {
           var code_to_be_compiled = textarea.value;
           var reader = new Reader(code_to_be_compiled);
           var retracted = false;
 
           while (true){
             var next_char = reader.nextChar();
             if (next_char == -1){
               break;
             }
             if (next_char == '!' && retracted == false){
               retracted = true;
               reader.retract();
             }
             log(next_char);
           }

         };
       };
     </script>
    </head>
    <body>
      <textarea id="source_code" style="width:600px; height:200px"></textarea>
      <button id="run">Run</button>
      <div id="log">
      </div>
    </body>
  </html>
