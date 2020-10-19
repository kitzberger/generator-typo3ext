'use strict'

const BaseGenerator = require('../base.js')
    , yosay = require('yosay')
    , chalk = require('chalk')
    , ejs = require('ejs');

module.exports = class extends BaseGenerator {

  async prompting() {
    await super._promptingBasic()
    await this._promptingCli()
  }

  async _promptingCli() {
    if (!this.options['skip-welcome-message']) {
      this.log(yosay('Alright! Let\'s create some slug column for our TYPO3 extension together, shall we?'))
    }

    var tcaPrompts = [
      {
        type    : 'input',
        name    : 'table',
        message : 'Name of table to extend?'
      },
      {
        type    : 'input',
        name    : 'slug_column',
        message : 'Name of new slug column?',
        default : 'slug'
      },
      {
        type    : 'input',
        name    : 'title_column',
        message : 'Name of column that slugs are based upon?',
        default : 'title'
      }
    ]

    this.tcaAnswers = await this.prompt(tcaPrompts)
  }

  writing() {
    var variables = this.config.getAll()
    variables.table = this.tcaAnswers.table
    variables.slug_column = this.tcaAnswers.slug_column
    variables.title_column = this.tcaAnswers.title_column

    var source = 'Configuration/TCA/Overrides/table.php'
      , target = 'Configuration/TCA/Overrides/' + variables.table + '.php'

    if (this.fs.exists(target) === false) {
      this.log('Creating ' + target)
      this.fs.write(target, this._getPhpBaseContent())
    }

    var sourceContent = this.fs.read(this.templatePath(source))
      , sourceContent = this._getTemplateSnippet(null, sourceContent)
      , targetContent = ejs.render(sourceContent, variables)

    this.log('Modifying ' + target)
    this.fs.append(target, targetContent);

    var source = 'Resources/Private/Language/table.xlf'
      , target = 'Resources/Private/Language/' + variables.table + '.xlf'

    this.log('Creating ' + target)
    this.fs.copyTpl(
      this.templatePath(source),
      this.destinationPath(target),
      variables
    )

    var source = this.templatePath('ext_tables.sql')
      , target = this.destinationPath('ext_tables.sql')

    if (this.fs.exists(target) === false) {
      this.fs.write(target, "")
    }

    this.log('Modifying ' + target)
    var sourceContent = this.fs.read(source)
      , targetContent = this.fs.read(target)

    var content = ejs.render(sourceContent, variables)

    this.fs.append(target, content)
  }

  install() {
  }

  end() {
    this.log(chalk.green('Done with everything!'))
  }
}
