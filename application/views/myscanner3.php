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
      Token.tokens.VAR_TOKEN = Token.tokens.MOD_TOKEN + 1;
      Token.tokens.TYPE_TOKEN = Token.tokens.VAR_TOKEN + 1;
      Token.tokens.BOOLLITERAL_TOKEN = Token.tokens.TYPE_TOKEN + 1;
      Token.tokens.IF_TOKEN = Token.tokens.BOOLLITERAL_TOKEN + 1;
      Token.tokens.ELSE_TOKEN = Token.tokens.IF_TOKEN + 1;
      Token.tokens.WHILE_TOKEN = Token.tokens.ELSE_TOKEN + 1;
      Token.tokens.PRINT_TOKEN = Token.tokens.WHILE_TOKEN + 1;
      Token.tokens.IDENTIFIER_TOKEN = Token.tokens.PRINT_TOKEN + 1;
      Token.tokens.PLUS_TOKEN = Token.tokens.IDENTIFIER_TOKEN + 1;
      Token.tokens.PLUSPLUS_TOKEN = Token.tokens.PLUS_TOKEN + 1;
      Token.tokens.PLUSASSIGN_TOKEN = Token.tokens.PLUSPLUS_TOKEN + 1;
      
      Token.tokens.MINUS_TOKEN = Token.tokens.PLUSASSIGN_TOKEN + 1;
      Token.tokens.MINUSMINUS_TOKEN = Token.tokens.MINUS_TOKEN + 1;
      Token.tokens.MINUSASSIGN_TOKEN = Token.tokens.MINUSMINUS_TOKEN + 1;
 
      Token.tokens.MULT_TOKEN = Token.tokens.MINUSASSIGN_TOKEN + 1;
      Token.tokens.DIV_TOKEN = Token.tokens.MULT_TOKEN + 1;
      Token.tokens.ASSIGN_TOKEN = Token.tokens.DIV_TOKEN + 1;
      Token.tokens.EQUAL_TOKEN = Token.tokens.ASSIGN_TOKEN + 1;
      Token.tokens.NOTEQUAL_TOKEN = Token.tokens.EQUAL_TOKEN + 1;
      Token.tokens.GREATER_TOKEN = Token.tokens.NOTEQUAL_TOKEN + 1;
      Token.tokens.GREATEREQUAL_TOKEN = Token.tokens.GREATER_TOKEN + 1; 


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
         this.bufferStr;
         this.state = Scanner.START_STATE;
       }

       Scanner.START_STATE = 0;
       Scanner.IDENTIFIER_STATE = Scanner.START_STATE + 1;
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
               if ((next_char >= 'a' && next_char <= 'z') || (next_char >= 'A' && next_char <= 'Z')){
                              this.state = Scanner.IDENTIFIER_STATE;
                              this.bufferStr = next_char;
                              return this.nextToken();
                }

               switch(next_char) {
                 case -1: return this.makeToken(Token.tokens.EOS_TOKEN);
                 case ':': return this.makeToken(Token.tokens.COLON_TOKEN);
                 case ';': return this.makeToken(Token.tokens.SEMICOLON_TOKEN);
		 case '(': return this.makeToken(Token.tokens.LEFTPAREN_TOKEN);
                 case ')': return this.makeToken(Token.tokens.RIGHTPAREN_TOKEN);
                 case '{': return this.makeToken(Token.tokens.LEFTBRACE_TOKEN);
                 case '}': return this.makeToken(Token.tokens.RIGHTBRACE_TOKEN);
                 case '%': return this.makeToken(Token.tokens.MOD_TOKEN);
		 case '\r': case '\n':
                   this.currLine++;
                 default:
              }
            }
           break;
         case Scanner.IDENTIFIER_STATE:
           var next_char = this.reader.nextChar();
           if ((next_char >= 'a' && next_char <= 'z') || (next_char >= 'A' && next_char <= 'Z')) {
                   this.bufferStr += next_char;
                   return this.nextToken();
                 }else{
                   this.state = Scanner.START_STATE;
                    
                    this.reader.retract();
            }

             switch(this.bufferStr) {
                        case "var":
                          return this.makeToken(Token.tokens.VAR_TOKEN);
                        case "int": case "bool":
                          return this.makeToken(Token.tokens.TYPE_TOKEN, this.bufferStr);
                        case "true": case "false": case "TRUE": case "FALSE":
                          return this.makeToken(Token.tokens.BOOLLITERAL_TOKEN, this.bufferStr.toLowerCase());
                        case "if":
                          return this.makeToken(Token.tokens.IF_TOKEN);
                        case "else":
                          return this.makeToken(Token.tokens.ELSE_TOKEN);
                        case "while":
                          return this.makeToken(Token.tokens.WHILE_TOKEN);
                        case "print":
                          return this.makeToken(Token.tokens.PRINT_TOKEN);
                        default:
                          return this.makeToken(Token.tokens.IDENTIFIER_TOKEN, this.bufferStr);
                     }
                    break;
                 
                  default:
                    break;
                }
           }
    </script>

     
<!--<script type="text/javascript">
            function Scanner(reader) {
                this.reader = reader;
                this.currentToken = new Token();
                this.currLine = 0;
                this.bufferStr;
                this.state = Scanner.START_STATE;
            }
            Scanner.START_STATE = 0;
            Scanner.IDENTIFIER_STATE = Scanner.START_STATE + 1;
            Scanner.prototype.makeToken = function(type, text) {
                this.currentToken.type = type;
                this.currentToken.text = text;
                return type;
            }
            Scanner.prototype.nextToken = function() {
                switch(this.state) {
                    case Scanner.START_STATE:
                        while(true) {
                            var next_char = this.reader.nextChar();
                            /* 遇到字母开头 表明可能是标示符或者是保留字 跳转状态*/
                            if((next_char >= 'a' && next_char <= 'z') || (next_char >= 'A' && next_char <= 'Z')) {
                                this.state = Scanner.IDENTIFIER_STATE;
                                this.bufferStr = next_char; //记录单词首字符
                                return this.nextToken();
                            }
                            switch(next_char) {
                                case -1 : return this.makeToken(Token.tokens.EOS_TOKEN);
                                case ':': return this.makeToken(Token.tokens.COLON_TOKEN);
                                case ';': return this.makeToken(Token.tokens.SEMICOLON_TOKEN);
                                case '(': return this.makeToken(Token.tokens.LEFTPAREN_TOKEN);
                                case ')': return this.makeToken(Token.tokens.RIGHTPAREN_TOKEN);
                                case '{': return this.makeToken(Token.tokens.LEFTBRACE_TOKEN);
                                case '}': return this.makeToken(Token.tokens.RIGHTBRACE_TOKEN);
                                case '%': return this.makeToken(Token.tokens.MOD_TOKEN);
                                case '\r': case '\n':
                                    this.currLine++;
                                default:
                                    //ignore other char
                            }
                        }
                        break;
                    case Scanner.IDENTIFIER_STATE:
                        var next_char = this.reader.nextChar();
                        if((next_char >= 'a' && next_char <= 'z') || (next_char >= 'A' && next_char <= 'Z')) {
                            this.bufferStr += next_char;
                            return this.nextToken();
                        } else {
                            /* 读取了连续由字母构成的单词 则一个单词字元读取结束 切换状态到开始状态 以便下次读取新的字元*/
                            this.state = Scanner.START_STATE;
                            /* 由于多读入了一个非字母字符来判断是否单词结束 所以要对字符读取器回退一个字符 */
                            this.reader.retract(); 
                        }

                        switch(this.bufferStr) {
                            case "var":
                                return this.makeToken(Token.tokens.VAR_TOKEN);
                            case "int": case "bool":
                                return this.makeToken(Token.tokens.TYPE_TOKEN, this.bufferStr);
                            case "true": case "false": case "TRUE": case "FALSE": 
                                return this.makeToken(Token.tokens.BOOLLITERAL_TOKEN, this.bufferStr.toLowerCase());
                            case "if": 
                                return this.makeToken(Token.tokens.IF_TOKEN);
                            case "else":
                                return this.makeToken(Token.tokens.ELSE_TOKEN);
                            case "while": 
                                return this.makeToken(Token.tokens.WHILE_TOKEN);
                            case "print" :
                                return this.makeToken(Token.tokens.PRINT_TOKEN);
                            default:
                                return this.makeToken(Token.tokens.IDENTIFIER_TOKEN, this.bufferStr);
                        }
                        break;
                    default:
                        break;
                }
            };
        </script>-->
     <script type="text/javascript">
       window.onload = function() {
         var textarea = document.getElementById("source_code");
         var run_button = document.getElementById("run");

         run_button.onclick = function() {
           var code_to_be_compiled = textarea.value;
           var reader = new Reader(code_to_be_compiled);
           var scanner = new Scanner(reader);
 
           while (true){
             var next_token = scanner.nextToken();
             if (next_token == Token.tokens.EOS_TOKEN){
               break;
             }
            
             var logStr = "Reader Token: " + Token.backwardMap[next_token];

             if (scanner.currentToken.text !== undefined){
               logStr += "(" + scanner.currentToken.text + ")";
             }  
            log(logStr);
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
