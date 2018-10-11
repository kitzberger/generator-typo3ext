'use strict'

const generator = require('yeoman-generator')
    , yosay = require('yosay')
    , chalk = require('chalk')
    , mkdirp = require('mkdirp')
    , glob = require('glob')

String.prototype.ucfirst = function() {
    return this.charAt(0).toUpperCase() + this.substr(1);
}

String.prototype.toCamelCase = function() {
    return this
        .replace(/([\s_-])(.)/g, function(match, $1, $2) { return $2.toUpperCase(); })
        .replace(/\s/g, '')
        .replace(/^(.)/, function($1) { return $1.toLowerCase(); });
}

String.prototype.ToCamelCase = function() {
    return this
        .replace(/([\s_-])(.)/g, function(match, $1, $2) { return $2.toUpperCase(); })
        .replace(/\s/g, '')
        .replace(/^(.)/, function($1) { return $1.toUpperCase(); });
}

Array.prototype.containsValue = function(value) {
  return this.indexOf(value) !== -1
}

module.exports = class extends generator {

  constructor(args, opts) {
    super(args, opts)

    this.option('skip-git', {desc: 'Do not automatically initialize git repositiry', default: false});
    this.option('skip-prompting', {desc: 'Do not ask me anything, just do it already!', default: false});

    // Defaults for user input storage .yo-rc.json
    this.config.defaults({
      ext_key                  : this.appname,
      t3_version               : '8.7.*',
      t3_language              : 'en',
    })
  }

  async prompting() {
    if (this.options.skipPrompting) {
      if (this.config.get('author_name')) {
        this.log(chalk.green('Skip prompting, reading .yo-rc.json instead!'))
        return;
      } else {
        this.log(chalk.yellow('Sorry, cannot skip prompting, no .yo-rc.json present!'))
      }
    }

    if (!this.options['skip-welcome-message']) {
      this.log(yosay('Hi there! Let\'s create some new TYPO3 extension together, shall we?'))
    }

    var prompts = [
      {
        type    : 'input',
        name    : 'author_name',
        message : 'First of all, who the heck are you?',
        store   : true
      },
      {
        type    : 'input',
        name    : 'author_mail',
        message : 'And what\'s your mail address?',
        store   : true
      },
      {
        type    : 'input',
        name    : 'vendor_name',
        message : 'Vendor name (github name)?',
        default : this.config.get('vendor_name')
      },
      {
        type    : 'input',
        name    : 'ext_key',
        message : 'Extension key?',
        default : this.config.get('ext_key')
      },
      {
        type    : 'input',
        name    : 'ext_name',
        message : 'Extension name?',
        default : this.config.get('ext_name') || this.config.get('ext_key')
      },
      {
        type    : 'input',
        name    : 'ext_desc',
        message : 'Extension description?',
        default : this.config.get('ext_desc')
      },
      {
        type    : 'list',
        name    : 't3_version',
        message : 'Which minimum version of TYPO3 you wanna support?',
        choices : [
          {
            name  : '9 LTS',
            value : '9.5.*',
          },
          {
            name  : '8 LTS',
            value : '8.7.*',
          },
          {
            name  : '7 LTS',
            value : '7.6.*'
          },
          {
            name  : '6 LTS',
            value : '6.2.*'
          }
        ],
        default: this.config.get('t3_version')
      },
      {
        type    : 'list',
        name    : 't3_language',
        message : 'What is the default language?',
        choices : [
          {
            name  : 'English',
            value : 'en'
          },
          {
            name  : 'German',
            value : 'de'
          }
        ],
        default: this.config.get('t3_language')
      }
    ]

    const answers = await this.prompt(prompts)

    console.dir(answers)

    // manually deal with the response, get back and store the results.
    // we change a bit this way of doing to automatically do this in the self.prompt() method.
    this.config.set('author_name'              , answers.author_name)
    this.config.set('author_mail'              , answers.author_mail)

    this.config.set('vendor_name'              , answers.vendor_name)
    this.config.set('ext_key'                  , answers.ext_key)
    this.config.set('ext_name'                 , answers.ext_name)
    this.config.set('ext_desc'                 , answers.ext_desc)
    this.config.set('package_name'             , answers.vendor_name.toLowerCase().replace('_', '-') + '/' + answers.ext_key.replace('_', '-'))
    this.config.set('VendorName'               , answers.vendor_name.ToCamelCase())
    this.config.set('ExtKey'                   , answers.ext_key.ToCamelCase())

    this.config.set('t3_version'               , answers.t3_version)
    this.config.set('t3_language'              , answers.t3_language)
    this.config.set('t3_language_1'            , answers.t3_language=='de'?'en':'de')

  }

  writing() {
    var metaFiles = glob.sync('*', {dot: true, nodir: true, cwd: this.templatePath()})

    if (metaFiles.length) {
      this.log(chalk.green('Creating ' + metaFiles.length + ' meta files'))

      for (var i=0; i<metaFiles.length; i++) {
        var targetName = metaFiles[i].replace(/^[_]/, '')
        this.log('Creating ' + targetName)
        this.fs.copyTpl(
          this.templatePath(metaFiles[i]),
          this.destinationPath(targetName),
          this.config.getAll()
        )
      }
    } else {
      this.log(chalk.red('No templates for meta files found!'))
    }
  }

  install() {
  }

  end() {
    this.log(chalk.green('Done with everything!'))
  }
}
