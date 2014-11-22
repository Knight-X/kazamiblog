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
           this.matchSemicolon();
           return new PrintNode(expressionNode, this.scanner.currLine);
         case Token.tokens.VAR_TOKEN:
           return this.parseVarExpression();
         case Token.tokens.IF_TOKEN:
           return this.parseIfExpression();
         case Token.tokens.WHILE_TOKEN:
           return this.parseWhileExpression();
         default:
           return this.parseCompoundExpression(0);
        }
      }
     Parser.prototype.parseVarExpression = function() {
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

     Parser.prototype.parseWhileExpression = function() {
       this.nextToken();

       var condition = this.parseParenExpression();
       var expressions = this.parseBlockExpression();

       return new WhileNode(condition, expressions);
     }

     Parser.prototype.parseParenExpression = function() {
       if (this.lookahead() != Token.tokens.LEFTPAREN_TOKEN) {
         log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting a ( "); 
       } else {
          this.nextToken();
       }
       var expression = this.parseExpression();
       if (this.lookahead() != Token.tokens.RIGHTPAREN_TOKEN){
          log("LIne " + this.scanner.currLine + ":(Syntax Error) Expecting a )");
       } else {
         this.nextToken();
       }

       return expression;
     }

     Parser.prototype.parseBlockExpression = function() {
       if (this.lookahead() != Token.tokens.LEFTBRACE_TOKEN) {
          log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting a { ");
	} else {
          this.nextToken();
        }
        var block = new ExpressionBlockNode();
        var blockExpression = this.parseExpressions(block);
	if (this.lookahead() != Token.tokens.RIGHTBRACE_TOKEN) {
          log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting a }");	
	} else {
           this.nextToken();
        }
        return block;
      }

      Parser.prototype.matchSemicolon = function() {
        if (this.lookahead() != Token.tokens.SEMICOLON_TOKEN) {
          log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting a ; at the end of expression");

          } else {
           this.nextToken();
         }
      }

      Parser.prototype.parseOperand = function() {

        var token = this.nextToken();
        switch (token) {
 	  case Token.tokens.INTLITERAL_TOKEN:
 	    return new IntNode(this.currentToken.text);
          case Token.tokens.BOOLLITERAL_TOKEN:
	    return new BoolNode(this.currentToken.text);
          case Token.tokens.IDENTIFIER_TOKEN:
	    var identifier = new IdentifierNode(this.currentToken.text);
            if (this.lookahead() == Token.tokens.MINUSMINUS_TOKEN){
		this.nextToken();
                return new PostDecrementNode(identifier);
	    } else if (this.lookahead() == Token.tokens.PLUSPLUS_TOKEN){
              this.nextToken();
              return new PostIncrementNode(identifier);
            } else {
		return identifier;
            }
          case Token.tokens.MINUSMINUS_TOKEN:
             if (this.lookahead() == Token.tokens.IDENTIFIER_TOKEN) {
               this.nextToken();
               return new PreDecrementNode(new IdentifierNode(this.currentToken.text));
             } else {
               log ("Line " + this.scanner.currLine + ":(Syntax Error) Expecting an identifier for --expression");

               return null;
             }

           case Token.tokens.PLUSPLUS_TOKEN:
	     if (this.lookahead() == Token.tokens.IDENTIFIER_TOKEN){
               this.nextToken();
 	       return new PreIncrementNode(new IdentifierNode(this.currentToken.text));
	     } else {
               log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting an identifier for ++ expression");
 		return null;
             }
        case Token.tokens.LEFTPAREN_TOKEN:
          var operand = new ParenNode(this.parseCompoundExpression(0));

          if (this.lookahead() == Token.tokens.RIGHTPAREN_TOKEN) {
	    this.nextToken();
          } else {
	    log("Line " + this.scanner.currLine + ":(Syntax Error) Expecting a ) ");
          }
          return operand;
        case Token.tokens.NOT_TOKEN:
          return new NotNode(this.parseOperand());

        case Token.tokens.MINUS_TOKEN:
	  return new NegateNode(this.parseOperand());
        case Token.tokens.SEMICOLON_TOKEN:
	  return null;

        default:
          log("Line " + this.scanner.currLine + ":(Syntax Error) Unexpected Token ");
	  return null;
        }
	 
       }

       Parser.prototype.parseCompoundExpression = function(rightBindingPower) {

         var operandNode = this.parseOperand();

         if (operandNode == null) {
             return null;
	 }

         var compoundExpressionNode = new CompoundNode();

         compoundExpressionNode.push(operandNode);

         var operator = this.lookahead();
         var leftBindingPower = this.getBindingPower(operator);

         if (leftBindingPower == -1){
           return compoundExpressionNode;
         }

         while (rightBindingPower < leftBindingPower){

           operator = this.nextToken();
	   compoundExpressionNode.push(this.createOperatorNode(operator));

           var node = this.parseCompoundExpression(leftBindingPower);

           compoundExpressionNode.push(node);

           operator = this.lookahead();

           leftBindingPower = this.getBindingPower(operator);

           if (leftBindingPower == -1) {
             return compoundExpressionNode;
           }

         }

         return compoundExpressionNode;
      }
     
      Parser.prototype.getBindingPower = function(token) {
        switch (token) {
	    case Token.tokens.MULT_TOKEN:
            case Token.tokens.DIV_TOKEN:
            case Token.tokens.MOD_TOKEN:
              return 200;

            case Token.tokens.PLUS_TOKEN:
            case Token.tokens.MINUS_TOKEN:
              return 190;

	    case Token.tokens.EQUAL_TOKEN:
	    case Token.tokens.NOTEQUAL_TOKEN:
              return 180;

            case Token.tokens.AND_TOKEN:
              return 170;

            case Token.tokens.OR_TOKEN:
              return 160;

            case Token.tokens.ASSIGN_TOKEN:
            case Token.tokens.MINUSASSIGN_TOKEN:
            case Token.tokens.PLUSASSIGN_TOKEN:
              return 150;

            default:
	      return -1;
          }
        }

        Parser.prototype.createOperatorNode = function(operator) {
          switch (operator) {
	    case Token.tokens.PLUS_TOKEN:
              return new OperatorPlusNode();
            case Token.tokens.MINUS_TOKEN:
              return new OperatorMinusNode();
            case Token.tokens.MULT_TOKEN:
              return new OperatorMultNode();
            case Token.tokens.DIV_TOKEN:
              return new OperatorDivNode();

            case Token.tokens.MOD_TOKEN:
              return new OperatorModNode();
            case Token.tokens.AND_TOKEN:
              return new OperatorAndNode();
            case Token.tokens.OR_TOKEN:
              return new OperatorOrNode();
            case Token.tokens.EQUAL_TOKEN:
              return new OperatorEqualNode();
            case Token.tokens.NOTEQUAL_TOKEN:
              return new OperatorNotEqualNode();
            case Token.tokens.ASSIGN_TOKEN:
              return new OperatorAssignNode();
            case Token.tokens.MINUSASSIGN_TOKEN:
              return new OperatorMinusAssignNode();
            case Token.tokens.PLUSASSIGN_TOKEN:
              return new OperatorPlusAssignNode();
            default:
              return null;
          }
       }

       Parser.prototype.skipError = function() {
          while (this.lookahead() != Token.tokens.NEWLINE_TOKEN && this.lookahead() != Token.tokens.EOS_TOKEN) {
            this.nextToken();
         }
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

       function BoolNode(data){
         this.data = data;
       }

       extend(BoolNode, Node);

       function VariableNode(varName, type, initExpressionNode) {
         this.varName = varName;
         this.type = type;
         this.initExpressionNode = initExpressionNode;
       }

       extend(VariableNode, Node);

       function IfNode(conditionExpression, expressions, elseExpressions) {
         this.conditionExpression = conditionExpression;
         this.expressions = expressions;
         this.elseExpressions = elseExpressions;
       }

       extend(IfNode, Node);

       function WhileNode(conditionExpression, expressions){
         this.conditionExpression = conditionExpression;
         this.expressions = expressions;
       }

       extend(WhileNode, Node);

       function IdentifierNode(identifier) {
         this.identifier = identifier;
       }

       function ParenNode(node){
         this.node = node;
       }

       extend(ParenNode, Node);

       function NegateNode(node) {
         this.node = node;
       }

       extend(NegateNode, Node);

       function CompoundNode(){
         this.nodes = [];
       }

       extend(CompoundNode, Node);
       CompoundNode.prototype.push = function (node) {
          this.nodes.push(node);
       }

       function OperatorNode() {
       }

       extend(OperatorNode, Node);

       function OperatorPlusNode() {
       }

       extend(OperatorPlusNode, OperatorNode);

       function OperatorMinusNode(){
       }

       extend(OperatorMinusNode, OperatorNode);

       function OperatorMultNode() {
       }

       extend(OperatorMultNode, OperatorNode);

       function OperatorDivNode() {
       }

       extend(OperatorDivNode, OperatorNode);

       function OperatorModNode(){
       }

       extend(OperatorModNode, OperatorNode);

       function OperatorAndNode() {
       }

       extend(OperatorAndNode, OperatorNode);

       function OperatorOrNode() {
       }

       extend(OperatorOrNode, OperatorNode);

       function OperatorEqualNode() {
       }

       function NotNode(node) {
         this.node = node;
       }

       extend(NotNode, Node);
       extend(OperatorEqualNode, OperatorNode);

       function OperatorNotEqualNode() {
      }

       extend(OperatorNotEqualNode, OperatorNode);
       
       function OperatorAssignNode() {
       }

        extend(OperatorAssignNode, OperatorNode);

      function OperatorPlusAssignNode() {
       }

       extend(OperatorPlusAssignNode, OperatorNode);

      function OperatorMinusAssignNode() {
      }

      extend(OperatorMinusAssignNode, OperatorNode);

      function PostIncrementNode(node) {
        this.node = node;
      }

      extend(PostIncrementNode, OperatorNode);

      function PreIncrementNode(node) {
        this.node = node;
      }

      extend(PreIncrementNode, OperatorNode);

      function PostDecrementNode(node) {
          this.node = node;
       }

      extend(PostDecrementNode, OperatorNode);

      function PreDecrementNode(node) {
         this.node = node;
       }

     extend(PreDecrementNode, OperatorNode);
    
       
     </script>
     <script type="text/javascript">
	function Analyser() {
	  this.vars = {};
        }

        Analyser.TYPE_BOOL = 1;
        Analyser.TYPE_INT = 2;
        Analyser.prototype.evaluateExpressionBlockNode = function(node) {
          if (!node) {
             return;
          }
          for (var i = 0; i < node.expressions.length; i++) {
	    this.evaluateExpressionNode(node.expressions[i]);
          }
        }

        Analyser.prototype.evaluateExpressionNode = function(node) {
          if (node instanceof VariableNode) {
            this.evaluateVariableNode(node);
          } else if (node instanceof PrintNode) {
            this.evaluatePrintNode(node);
          } else if (node instanceof CompoundNode) {
            this.evaluateCompoundNode(node);
          } else if (node instanceof IdentifierNode) {
            this.evaluateIdentifierNode(node);
          } else if (node instanceof IntNode) {
            this.evaluateIntNode(node);
          } else if (node instanceof BoolNode) {
            this.evaluateBoolNode(node);
          } else if (node instanceof PostIncrementNode) {
            this.evaluatePostIncrementNode(node);
          } else if (node instanceof PreIncrementNode) {
            this.evaluatePreIncrementNode(node);
          } else if (node instanceof PostDecrementNode) {
            this.evaluatePostDecrementNode(node);
          } else if (node instanceof PreDecrementNode) {
            this.evaluatePreDecrementNode(node);
          } else if (node instanceof NegateNode) {
            this.evaluateNegateNode(node);
          } else if (node instanceof NotNode) {
            this.evaluateNotNode(node);
          } else if (node instanceof ParenNode) {
            this.evaluateParenNode(node);
          } else if (node instanceof IfNode) {
            this.evaluateIfNode(node);
          } else if (node instanceof WhileNode) {
            this.evaluateWhileNode(node);
          }
       
        }
        Analyser.prototype.evaluateIntNode = function(node) {
          node.valueType = Analyser.TYPE_INT;
        }

        Analyser.prototype.evaluateBoolNode = function(node) {
          node.valueType = Analyser.TYPE_BOOL;
        }

        Analyser.prototype.evaluatePrintNode = function(node) {
            this.evaluateExpressionNode(node.expressionNode);
        }
       
        Analyser.prototype.evaluateNegateNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
        }

        Analyser.prototype.evaluateNotNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
        }

        Analyser.prototype.evaluateParenNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
        }

        Analyser.prototype.evaluatePostIncrementNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
        }

        Analyser.prototype.evaluatePreIncrementNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
        }

       Analyser.prototype.evaluatePostDecrementNode = function(node) {
            this.evaluateExpressionNode(node.node);
            node.valueType = node.node.valueType;
       }

       Analyser.prototype.evaluatePreDecrementNode = function(node) {
           this.evaluateExpressionNode(node.node);
           node.valueType = node.node.valueType;
       }

        Analyser.prototype.evaluateCompoundNode = function(node) {
          var type = null;
          var operator = null;
          for (var i = 0; i < node.nodes.length; i++) {
            var subNode = node.nodes[i];
            this.evaluateExpressionNode(subNode);
            if (type == null) {
              type = subNode.valueType;
            } else if (subNode instanceof OperatorNode) {
              operator = subNode;
            } else if (operator instanceof OperatorPlusNode ||
                       operator instanceof OperatorMinusNode || 
                       operator instanceof OperatorMultNode  ||
                       operator instanceof OperatorDivNode ||
		       operator instanceof OperatorModNode) {
              if (type != Analyser.TYPE_INT || subNode.valueType != Analyser.TYPE_INT) {
              log("Line " + operator.line + ":(Semantic Error) " + " Require Integers on both sides of arithmetic operator");
              }
                type = Analyser.TYPE_INT;
             } else if (operator instanceof OperatorAndNode || operator instanceof OperatorOrNode) {
               if (type != Analyser.TYPE_BOOL || subNode.valueType != Analyser.TYPE_BOOL){
              log("Line " + operator.line + ":(Semantic Error) " + " Require Booleans on both sides of logical operator");
              }
             type = Analyser.TYPE_BOOL;
            } else if (operator instanceof OperatorEqualNode || operator instanceof OperatorNotEqualNode) {
               if (type != subNode.valueType) {
               log("Line " + operator.line + ":(Semantic Error) " + " Require the type on both sides of comparison operator to be the same");
               }
               type = Analyser.TYPE_BOOL;
            } else if (operator instanceof OperatorAssignNode) {
               if (type != subNode.valueType) {
                 log("Line " + operator.line + ":(Semantic Error) " + " Require the type on both sides of \"=\" to be the same'");
               }
            } else if (operator instanceof OperatorMinusAssignNode || operator instanceof OperatorPlusAssignNode) {
               if (type != Analyser.TYPE_INT || subNode.valueType != Analyser.TYPE_INT) { 
                 log("Line " + operator.line + ":(Semantic Error) " + "Require the type on both sides of plus/minus assignment operator to be the same");
               }
            }
          }
          node.valueType = type;
        }

       Analyser.prototype.evaluateIfNode = function(node) {
         this.evaluateExpressionNode(node.conditionExpression);
         if (node.conditionExpression.valueType != Analyser.TYPE_BOOL) {
           log("Line " + node.conditionExpression.line + ":(Semantic Error) " + "Require codition must be Boolean type");
         }
         this.evaluateExpressionBlockNode(node.expressions);
         this.evaluateExpressionBlockNode(node.elseExpressions);
       }
       
       Analyser.prototype.evaluateWhileNode = function(node) {
         this.evaluateExpressionNode(node.coditionExpression);
         if (node.conditionExpression.valueType != Analyser.TYPE_BOOL) {
           log("Line " + node.conditionExpression.line + ":(Semantic Error)" + " The condition must be of Boolean Type.");
         }
         this.evaluateExpressionBlockNode(node.expressions);
       }
       Analyser.prototype.evaluateIdentifierNode = function(node) {
         if (!this.vars[node.identifier]) {
            log("Line " + node.line + ":(Semantic Error) " + node.identifier + " should be declared before using.");
         } else {
           node.valueType = this.vars[node.identifier].valueType;
         }
       }

      
        Analyser.prototype.evaluateVariableNode = function(node) {
          if (this.vars[node.varName]) {
            log("Line " + node.line + ":(Semantic Error) " + node.varName + " has been declared already.");
          } else {
            this.vars[node.varName] = node;
          }
          if (node.initExpressionNode) {
             this.evaluateExpressionNode(node.initExpressionNode)
            if (node.type == "bool" && node.initExpressionNode.valueType == Analyser.TYPE_INT) {
              log("Line " + node.line + ":(Semantic Error) " + node.varName + " is bool type, can't assign int value.");
            }  else if (node.type == "int" && node.initExpressionNode.valueType == Analyser.TYPE_BOOL) {
               log("Line " + node.line + ":(Semantic Error) " + node.varName + " is int type, cant assign bool value.");
            }
          } else {
            if (node.type == "bool") {
              node.initExpressionNode = new BoolNode("false");
            } else if (node.type == "int"){
              node.initExpressionNode = new IntNode(0);
            }
         }
         node.valueType = node.type == "bool" ? Analyser.TYPE_BOOL : Analyser.TYPE_INT;
             
         }
         </script>
         <script type="text/javascript">
           function Compiler() {
             this.lineBreak = "<br/>";
             this.register = 0;
             this.vars = {};
           }

           Compiler.prototype.getMachineCode = function(expressionBlockNode) {
             this.code = "";
             this.evaluateExpressionBlockNode(expressionBlockNode);
             return this.code;
           }

           Compiler.prototype.getNextRegister = function() {
             return "$" + this.register++;
           }

           Compiler.prototype.writeln = function(code) {
             this.code = this.code + code + this.lineBreak;
             log(code);
           }

           Compiler.prototype.evaluateExpressionBlockNode = function(node) {
             if (!node) {
               return;
             }
             for (var i = 0; i < node.expressions.length; i++) {
               this.evaluateExpressionNode(node.expressions[i]);
             }
           }
           Compiler.prototype.evaluateExpressionNode = function(node) {
             if (node instanceof VariableNode) {
               return this.evaluateVariableNode(node);
             } else if (node instanceof PrintNode) {
               return this.evaluatePrintNode(node);
             } else if (node instanceof CompoundNode) {
               return this.evaluateCompoundNode(node);
             } else if (node instanceof IdentifierNode) {
               return this.evaluateIdentifierNode(node);
             } else if (node instanceof IntNode) {
               return this.evaluateIntNode(node);
             } else if (node instanceof BoolNode) {
               return this.evaluateBoolNode(node);
             } else if (node instanceof PostIncrementNode) {
               return this.evaluatePostIncrementNode(node);
             } else if (node instanceof PreIncrementNode) {
               return this.evaluatePreIncrementNode(node);
             } else if (node instanceof PostDecrementNode) {
               return this.evaluatePostDecrementNode(node);
             } else if (node instanceof PreDecrementNode) {
               return this.evaluatePreDecrementNode(node);
             } else if (node instanceof NegateNode) {
               return this.evaluateNegateNode(node);
             } else if (node instanceof NodNode) {
               return this.evaluateNotNode(node);
             } else if (node instanceof ParenNode) {
               return this.evaluateParenNode(node);
             } else if (node instanceof IfNode) {
               return this.evaluateIfNode(node);
             } else if (node instanceof WhileNode) {
               return this.evaluateWhileNode(node);
             }
           }

           Compiler.prototype.evaluateIntNode = function(node) {
             var register = this.getNextRegister();
             this.writeln("lwi " + register + ", " + node.data + ";");
             return register;
           }

           Compiler.prototype.evaluateBoolNode = function(node) {
             var register = this.getNextRegister();
             this.writeln("lwi " + register + ", " + (node.data == "true" ? 1 : 0) + ";");
             return register;
           }

           Compiler.prototype.evaluatePrintNode = function(node) {
             var register = this.evaluateExpressionNode(node.expressionNode);
             this.writeln("print " + register + ";");
           }

           Compiler.prototype.evaluateNegateNode = function(node) {
             var register = this.evaluateExpressionNode(node);
           }

           Compiler.prototype.evaluateNotNode = function(node) {
             var register = this.evaluateExpressionNode(node.node);
           }

           Compiler.prototype.evaluateParenNode = function(node) {
             var register = this.evaluateExpressionNode(node.node);
           }

           Compiler.prototype.evaluatePostIncrementNode = function(node){
             var register = this.evaluateExpressionNode(node.node);
           }

           Compiler.prototype.evaluatePreIncrementNode = function(node) {
             var register = this.evaluateExpressionNode(node.node);
           }

           Compiler.prototype.evaluatePostDecrementNode = function(node) {
             var register = this.evaluateExpressionNode(node.node);
           }

          Compiler.prototype.evaluatePreDecrementNode = function(node) {
             var register = this.evaluateExpressionNode(node.node);
          }

          Compiler.prototype.evaluateCompoundNode = function(node) {
             var operator = null;
             var resultRegister = null;

             for (var i = 0; i < node.nodes.length; i++) {
               var subNode = node.nodes[i];
               var currRegister = this.evaluateExpressionNode(subNode);
                
               if (subNode instanceof OperatorNode) {
                 operator = subNode;
               } else if (resultRegister == null) {
                 resultRegister = this.getNextRegister();
                 this.writeln("move " + resultRegister + ", " + currRegister + ";");
               } else if (operator instanceof OperatorPlusNode) {
                 this.writeln("add " + resultRegister + ", " + resultRegister + ", " + currRegister);
               } else if (operator instanceof OperatorMulNode) {
               } else if (operator instanceof OperatorDivNode) {
               } else if (operator instanceof OperatorModNode) {
               } else if (operator instanceof OperatorAndNode) {
               } else if (operator instanceof OperatorOrNode) {
               } else if (operator instanceof OperatorEqualNode) {
               } else if (operator instanceof OperatorNotEqualNode) {
               } else if (operator instanceof OperatorAssignNode) {
               } else if (operator instanceof OperatorMinusAssignNode) {
               } else if (operator instanceof OperatorPlusAssignNode) {
               }
             }
           return resultRegister;
         }

         Compiler.prototype.evaluateIfNode = function(node) {
           this.evaluateExpressionNode(node.conditionExpression);
           if (node.conditionExpression.valueType != Compiler.TYPE_BOOL){
             log("Line " + node.conditionExpression.line + ":(Semantic Error)" + " The condition must be of Boolean Type.");
           }

           this.evaluateExpressionBlockNode(node.expressions);
           this.evaluateExpreesionBlockNode(node.elseExpressions);
         }

        Compiler.prototype.evaluateWhileNode = function(node) {
           this.evaluateExpressionNode(node.conditionExpression);
           if (node.conditionExpression.valueType != Compiler.TYPE_BOOL) {
             log("Line " + node.conditionExpression.line + ":(Semantic Error)" + "The condition must be of boolean type");
           } 
          this.evaluateExpressionBlockNode(node.expressions);
        }

        Compiler.prototype.evaluateIdentifierNode = function(node) {
          if (!this.vars[node.identifier]) {
           log("Line " + node.line + ":(in compilation Semantic Error) " + node.identifier + " Should be declared before using");
          } else {
            node.valueType = this.vars[node.identifier].valueType;
          }
        }

       Compiler.prototype.evaluateVariableNode = function(node) {
         if (this.vars[node.varName]) {
           log("Line " + node.line + ":(Semantic Error) " + node.varName + " has been declared already.");

         } else {
            this.vars[node.varName] = node;
         }
      
         if (node.initExpressionNode) {
            this.evaluateExpressionNode(node.initExpressionNode);
            if (node.type == "bool" && node.initExpressionNode.valueType == Compiler.TYPE_INIT) {
             log("Line " + node.line + ":(Semantic Error) " + node.varName + "is bool type, can't assign int value.");
            } else if (node.type == "int" && node.initExpressionNode.valueType == Compiler.TYPE_BOOL) {
             log("Line " + node.line + ":(Semantic Error) " + node.varName + " is int type, cant' assign bool value.");
              }
           } else {
              if (node.type == "bool") {
                node.initExpressionNode = new BoolNode("false");
              } else if (node.type == "int"){ 
                node.initExpressionNode = new IntNode(0);
           }
          }

           node.valueType = node.type == "bool" ? Compiler.TYPE_BOOL : Compiler.TYPE_INT;
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
 	   var parser = new Parser(scanner);
	   var expressionBlockNode = parser.parse();
	   console.log(expressionBlockNode);
           var analyser = new Analyser();
           analyser.evaluateExpressionBlockNode(expressionBlockNode);
           var compiler = new Compiler();
           var code = compiler.getMachineCode(expressionBlockNode);
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
