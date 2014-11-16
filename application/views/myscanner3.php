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
      
      Token.tokens.LESS_TOKEN = Token.tokens.GREATEREQUAL_TOKEN + 1;
      Token.tokens.LESSEQUAL_TOKEN = Token.tokens.LESS_TOKEN + 1;
      Token.tokens.AND_TOKEN = Token.tokens.LESSEQUAL_TOKEN + 1;
      Token.tokens.OR_TOKEN = Token.tokens.AND_TOKEN + 1;
      Token.tokens.NOT_TOKEN = Token.tokens.OR_TOKEN + 1;

      Token.tokens.LINECOMMENT_TOKEN = Token.tokens.NOT_TOKEN + 1;
      Token.tokens.BLOCKCOMMENT_TOKEN = Token.tokens.LINECOMMENT_TOKEN + 1;

      Token.tokens.ERROR_TOKEN = Token.tokens.BLOCKCOMMENT_TOKEN + 1;
      Token.tokens.NEWLINE_TOKEN = Token.tokens.ERROR_TOKEN + 1;

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
         this.bufferStr = "";
         this.state = Scanner.START_STATE;
       }

       Scanner.START_STATE = 0;
       Scanner.IDENTIFIER_STATE = Scanner.START_STATE + 1;
       Scanner.MULTISYMBOL_STATE = Scanner.IDENTIFIER_STATE + 1;
       Scanner.SLASH_STATE = Scanner.MULTISYMBOL_STATE + 1;
       Scanner.INTLITERAL_STATE = Scanner.SLASH_STATE + 1;

       Scanner.prototype.makeToken = function(type, text){
         this.currentToken.type = type;
         this.currentToken.text = text;
         return type;
       }

       Scanner.prototype.nextToken = function() {
         switch(this.state) {
           case Scanner.START_STATE:
               var next_char = this.reader.nextChar();
               if ((next_char >= 'a' && next_char <= 'z') || (next_char >= 'A' && next_char <= 'Z')){
                              this.state = Scanner.IDENTIFIER_STATE;
                              this.bufferStr = next_char;
                              return this.nextToken();
                }
               if (next_char >= '0' && next_char <= '9'){
                 this.state = Scanner.INTLITERAL_STATE;
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
                 case '*': return this.makeToken(Token.tokens.MULT_TOKEN);
                 case '%': return this.makeToken(Token.tokens.MOD_TOKEN);
		 case '\r': case '\n':
                   this.currLine++;
                   return this.makeToken(Token.tokens.NEWLINE_TOKEN);
                 default:
                   this.state = Scanner.MULTISYMBOL_STATE;
                   this.bufferStr = next_char;
                   return this.nextToken();
              }
            
           break;
         case Scanner.INTLITERAL_STATE:
           var next_char = this.reader.nextChar();
           if (next_char >= '0' && next_char <= '9'){
             this.bufferStr += next_char;
             return this.nextToken();
           }else{
             this.state = Scanner.START_STATE;
             this.reader.retract();

             return this.makeToken(Token.tokens.INTLITERAL_TOKEN, parseInt(this.bufferStr));
           }
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
                case Scanner.MULTISYMBOL_STATE:
                  this.state = Scanner.START_STATE;
                  switch(this.bufferStr) {
                    case '+':
                       var c = this.reader.nextChar();
                       if (c == '+'){
                         return this.makeToken(Token.tokens.PLUSPLUS_TOKEN);
                       }else if (c == '='){
                         return this.makeToken(Token.tokens.PLUSASSIGN_TOKEN);
                       }else {
                         this.reader.retract();
                         return this.makeToken(Token.tokens.PLUS_TOKEN);
                       }
                  case '-':
                     var c = this.reader.nextChar();
                     if (c == '-') {
                       return this.makeToken(Token.tokens.MINUSMINUS_TOKEN);
                     }else if (c == '='){
                       return this.makeToken(Token.tokens.MINUSASSIGN_TOKEN);
                     }else {
                       this.reader.retract();
                       return this.makeToken(Token.tokens.MINUS_TOKEN);
                     }
                  case '=':
                     var c = this.reader.nextChar();
                     if (c == '=') {
                       return this.makeToken(Token.tokens.EQUAL_TOKEN);
                     }else {
                       this.reader.retract();
                       return this.makeToken(Token.tokens.ASSIGN_TOKEN);
                      }
                  case '!':
                     var c = this.reader.nextChar();
                     if (c == '=') {
                       return this.makeToken(Token.tokens.NOTEQUAL_TOKEN);
                     }else {
                       this.reader.retract();
                      return this.makeToken(Token.tokens.NOT_TOKEN); 
                     }
                  case '<':
                     var c = this.reader.nextChar();
                     if (c == '='){
                       return this.makeToken(Token.tokens.LESSEQUAL_TOKEN);
                     }else{
                       this.reader.retract();
                       return this.makeToken(Token.tokens.LESS_TOKEN);
                     }
                  case '>':
                      var c = this.reader.nextChar();
                      if (c == '='){
                        return this.makeToken(Token.tokens.GREATEREQUAL_TOKEN);
                      }else {
                        this.reader.retract();
                        return this.makeToken(Token.tokens.GREATER_TOKEN);
                      }
                   case '|':
                      var c = this.reader.nextChar();
                      if (c == '|') {
                        return this.makeToken(Token.tokens.OR_TOKEN);
                      }else {
                        if (c != -1){
                          return this.reader.retract();
                        }
                          return this.makeToken(Token.tokens.ERROR_TOKEN, "Line" + this.currLine + ": Missing a |");
                      }
                   case '&':
                      var c = this.reader.nextChar();
                      if (c == '&'){
                        return this.makeToken(Token.tokens.AND_TOKEN);
                      }else {
                        if (c != -1){
                          this.reader.retract();
                        }
                        return this.makeToken(Token.tokens.ERROR_TOKEN, "line " + this.currLine + ": Missing a &");
                      }
                  case '/':
                    this.state = Scanner.SLASH_STATE;
                    return this.nextToken();
                  default:
                    return this.nextToken();
                }
               break;
            case Scanner.SLASH_STATE:
              this.state = Scanner.START_STATE;
              var c = this.reader.nextChar();
              this.bufferStr = "";
              switch(c){
                case '/':
                   do {
                     var d = this.reader.nextChar();
                     this.bufferStr += d;
                   } while(d != '\r' && d != '\n');
                   this.currLine++;
                   return this.makeToken(Token.tokens.LINECOMMENT_TOKEN, this.bufferStr);
                case '*':
                   while (true){
                     var d = this.reader.nextChar();
                     if (d == '*'){
                       if (this.reader.nextChar() == '/'){
                         return this.makeToken(Token.tokens.BLOCKCOMMENT_TOKEN, this.bufferStr);
                       }else {
                         this.reader.retract();
                       }
                     }else if (d == -1){
                       return this.makeToken(Token.tokens.BLOCKCOMMENT_TOKEN, this.bufferStr);
                     } else{
                       this.bufferStr += d;
                       if (d == '\r' || d == '\n') {
                        this.currLine++;
                       }
                     }
                   }
               default:
                 return this.makeToken(Token.tokens.DIV_TOKEN);
               }
               break;
             default:
               break;
            }
           }
    </script>

    <script type="text/javascript">
       function Parser(scanner){
         this.scanner = scanner;
         this.currentToken = new Token();
         this.lookaheadToken = new Token();
         this.lookaheadToken.used = true;
       }
       Parser.prototype.nextToken = function() {
         if (this.lookaheadToken.used) {
           do {
                 var token = this.scanner.nextToken();
   	   } while (token == Token.tokens.BLOCKCOMMENT_TOKEN || token == Token.tokens.LINECOMMENT_TOKEN || token == Token.tokens.ERROR_TOKEN);
           
           this.currentToken.type = this.scanner.currentToken.type;
	   this.currentToken.text = this.scanner.currentToken.text;
           return token;
	 } else {
           this.currentToken.type = this.lookaheadToken.type;
           this.currentToken.text = this.lookaheadToken.text;
           this.lookaheadToken.used = true;
           return this.currentToken.type;
	}
      }

     Parser.prototype.lookahead = function() {
	if (this.lookaheadToken.used) {
          do {
	      var token = this.scanner.nextToken();
          } while (token == Token.tokens.BLOCKCOMMENT_TOKEN || token == Token.tokens.LINECOMMENT_TOKEN || token == Token.tokens.ERROR_TOKEN);

          this.lookaheadToken.type = this.scanner.currentToken.type;
          this.lookaheadToken.text = this.scanner.currentToken.text;
          this.lookaheadToken.used = false;
          return token;
        } else {
          return this.lookaheadToken.type;
        }
      }
     Parser.prototype.parse = function() {
        var rootBlock = new ExpressionBlockNode();
        this.parseExpressions(rootBlock);
        return rootBlock;
     }

     Parser.prototype.parseExpressions = function(expressionBlockNode) {
       while (this.lookahead() != Token.tokens.RIGHTBRACE_TOKEN && 
              this.lookahead() != Token.tokens.EOS_TOKEN){
         
         if (this.lookahead() == Token.tokens.NEWLINE_TOKEN) {
           this.nextToken();
           continue;
         }
        var expressionNode = this.parseExpression();
        if (expressionNode){
           expressionBlockNode.push(expressionNode);
         }
       }
      }

     Parser.prototype.parseExpression = function() {
       switch (this.lookahead()) {
         case Token.tokens.PRINT_TOKEN:
           var printToken = this.nextToken();
           var expressionNode = this.parseExpression();
           if (expressionNode == undefined){
              log("line " + this.scanner.currLine + ":(Syntax error) Missing an expression after \"print\"");
           }
           this.matchSmicolon();
           return new PrintNode(expressionNode);
         case Token.tokens.VAR_TOKEN:
           return this.parseVarExpression();
         case Token.tokens.IF_TOKEN:
           return this.parseIfExpression();
         case Token.tokens.WHILE_TOKEN:
           return this.parseWhileExpression();
         default:
           return this.parseCompoundExpression(0);

         case Token.tokens.INTLITERAL_TOKEN:
           var intToken = this.nextToken();
           return new IntNode(this.currentToken.text);
        }
      }
     Parse.prototype.parseVarExpression = function() {
       this.nextToken();
       
       if (this.lookahead() == Token.tokens.IDENTIFIER_TOKEN) {
         this.nextToken();
         var varName = this.currentToken.text;
         if (this.lookahead() != Token.tokens.COLON_TOKEN) {
         log("Line " + this.scanner.currLine + ":(Syntax error expecting a : after " + varName);
         this.skipError();
         return;
        }
        this.nextToken();
        if (this.lookahead() != Token.tokens.TYPE_TOKEN){
          log("Line " + this.scanner.currLine + ":(Syntax Error expecting a \" int \" or \"bool\" after: ");
          this.skipError();
          return;
        }
        this.nextToken();
        var type = this.currentToken.text;
        var initNode;
        if (this.lookahead() == Token.tokens.ASSIGN_TOKEN) {
          this.nextToken();
          initNode = this.parseExpression();
        }

        this.matchSemicolon();
        return new VariableNode(varName, type, initNode);
      }
      log("Line " + this.scanner.currLine + ":(Syntax Error ) Expecting an identifier after \" var \"");
       this.skipError();
      }
      Parser.prototype.parseIfExpression = function() {
       this.nextToken();

       var condition = this.parseParenExpression();
       var expressions = this.parseBlockExpression();

       var elseExpressions;
       if (this.lookahead() == Token.tokens.ELSE_TOKEN) {
         this.nextToken();
	 elseExpressions = this.parseBlockExpression();
       }
       return new IfNode(condition, expressions, elseExpressions);
     }
     </script>
     <script type="text/javascript">
       function extend(subClass, baseClass) {
         function inheritance() {}

         inheritance.prototype = baseClass.prototype;

         subClass.prototype = new inheritance();
         subClass.prototype.constructor = subClass;
         subClass.baseConstructor = baseClass;
         subClass.superClass = baseClass.prototype;
       }
       function Node(param) {
       }

       function ExpressionBlockNode() {
         ExpressionBlockNode.baseConstructor.call(this, "test");
         this.expressions = [];
       }

       extend(ExpressionBlockNode, Node);

       ExpressionBlockNode.prototype.push = function(expression) {
         this.expressions.push(expression);
       }

       ExpressionBlockNode.prototype.iterate = function(func) {
         for (var i = 0, l = this.expressions.length; i < l; i++){
           var expression = this.expression[i];
           func(expression, i);
         }
       }

       function PrintNode(expressionNode) {
         this.expressionNode = expressionNode;
       }

       extend(PrintNode, Node);

       function IntNode(data) {
         this.data = data;
       }

       extend(IntNode, Node);
       
     </script>
     <script type="text/javascript">
       window.onload = function() {
         var textarea = document.getElementById("source_code");
         var run_button = document.getElementById("run");

         run_button.onclick = function() {
           var code_to_be_compiled = textarea.value;
           var reader = new Reader(code_to_be_compiled);
           var scanner = new Scanner(reader);
 	   var parser = new Parser(scanner);
	   var expressionBlockNode = parser.parse();
	   console.log(expressionBlockNode);
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
