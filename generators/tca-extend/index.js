'use strict'

const BaseGenerator = require('../base.js')
    , yosay = require('yosay')
    , chalk = require('chalk')
    , ejs = require('ejs');

module.exports = class TcaGenerator extends BaseGenerator {

  async prompting() {
    await super._promptingBasic()
    await this._promptingCli()
  }

  async _promptingCli() {
    if (!this.options['skip-welcome-message']) {
      this.log(yosay('Alright! Let\'s create some CLI task for our TYPO3 extension together, shall we?'))
    }

    var tcaPrompts = [
      {
        type    : 'input',
        name    : 'table',
        message : 'Name of table to extend?'
      },
      {
        type    : 'input',
        name    : 'new_extbase_type',
        message : 'Name of new extbase type (if that\'s what you want)?'
      },
      {
        type    : 'input',
        name    : 'new_palette',
        message : 'Name of new palette (if that\'s what you want)?'
      }
    ]

    this.tcaAnswers = await this.prompt(tcaPrompts)
  }

  writing() {
    var variables = this.config.getAll()
    variables.table = this.tcaAnswers.table
    variables.new_extbase_type = this.tcaAnswers.new_extbase_type
    variables.new_palette = this.tcaAnswers.new_palette

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
