SyntaxHighlighter.brushes.Twig = function()
{
  var standard_funcs	= 'range cycle constant random attribute block parent dump';
  var custom_funcs		= 'menu'
  var tags				= 'for in endfor if endif elseif else macro endmacro import as filter endfilter set endset extends block endblock include with from use spaceless endspaceless autoescape endautoescape raw endraw';
  var filters			= 'date e format replace url_encode json_encode convert_encoding title capitalize nl2br upper lower striptags join reverse length sort default keys escape raw merge';
 
  this.regexList = [
  		{ regex: new RegExp(this.getKeywords(standard_funcs), 'gmi'),	css: 'color2' },
  		{ regex: new RegExp(this.getKeywords(custom_funcs), 'gmi'),		css: 'color2' },
  		{ regex: new RegExp(this.getKeywords(tags), 'gmi'),				css: 'keyword' },
  		{ regex: new RegExp(this.getKeywords(filters), 'gmi'),			css: 'color3' },
      	{ regex: /[{}%]{2}/gm,                                          css: 'color1' },
      	{ regex: /[a-z]+\.[a-z]+/gm,                                    css: 'variable' },
      	{ regex: SyntaxHighlighter.regexLib.doubleQuotedString,			css: 'string' },		// double quoted strings
		{ regex: SyntaxHighlighter.regexLib.singleQuotedString,			css: 'string' },		// single quoted strings
		{ regex: /{#(.*)#}/gm,                                          css: 'comments' }
      ];
};
SyntaxHighlighter.brushes.Twig.prototype = new SyntaxHighlighter.Highlighter();
SyntaxHighlighter.brushes.Twig.aliases  = ['twig'];