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
      function Token(type, text){
        this.type = type;
        this.text = text;
      }

      Token.tokens = {};
      Token.tokens.EOS_TOKEN = 1;
      
      Token.tokens.COLON_TOKEN = Token.tokens.EOS_TOKEN + 1;
      Token.tokens.SEMICOLON_TOKEN = Token.tokens.COLON_TOKEN + 1;
      Token.tokens.LEFTPAREN_TOKEN = Token.tokens.SEMICOLON_TOKEN + 1;
      Token.tokens.RIGHTPAREN_TOKEN = Token.tokens.LEFTPAREN_TOKEN + 1;
      Token.tokens.LEFTBRACE_TOKEN = Token.tokens.RIGHTPAREN_TOKEN + 1;
      Token.tokens.RIGHTBRACE_TOKEN = Token.tokens.LEFTBRACE_TOKEN + 1;
      Token.tokens.MOD_TOKEN = Token.tokens.RIGHTBRACE_TOKEN + 1;

      Token.backwardMap = {};

      for (var x in Token.tokens){
         Token.backwardMap[Token.tokens[x]] = x;
      } 
    </script>
     <script type="text/javascript">
       function Scanner(reader){
         this.reader = reader;
         this.currentToken = new Token();
         this.currLine = 0;
         this.state = Scanner.START_STATE;
       }

       Scanner.START_STATE = 0;
       Scanner.prototype.makeToken = function(type, text){
         this.currentToken.type = type;
         this.currentToken.text = text;
         return type;
       }

       Scanner.prototype.nextToken = function() {
         switch(this.state) {
           case Scanner.START_STATE:
             while (true) {
               var next_char = this.reader.nextChar();
               switch(next_char) {
                 case -1: return this.makeToken(Token.tokens.EOS_TOKEN);
                 case ':': return this.makeToken(Token.tokens.COLON_TOKEN);
                 case ';': return this.makeToken(Token.tokens.SEMICOLON_TOKEN);
		 case '(': return this.mkaeToken(Token.tokens.LEFTPAREN_TOKEN);
                 case ')': return this.makeToken(Token.tokens.RIGHTPAREN_TOKEN);
                 case '{': return this.makeToken(Token.tokens.LEFTBRACE_TOKEN);
                 case '}': return this.makeToken(Token.tokens.RIGHTBRACE_TOKEN);
                 case '%': return this.makeToken(Token.tokens.MOD_TOKEN);
		 case '\r': case '\n':
                   this.currLine++;
                 default:
              }
            }
         default:
           break;
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
           var scanner = new Scanner(reader);
 
           while (true){
             var next_token = reader.nextToken();
             if (next_token == Token.tokens.EOS_TOKEN){
               break;
             }
            
             log("Reader Token: " Token.backwardMap[next_token]);
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
