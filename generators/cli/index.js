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
      this.log(yosay('Alright! Let\'s create some CLI task for our TYPO3 extension together, shall we?'))
    }

    var cliPrompts = [
      {
        type    : 'input',
        name    : 'controller',
        message : 'Name of CLI command controller?'
      },
      {
        type    : 'input',
        name    : 'command',
        message : 'Name of CLI command itself?'
      }
    ]

    this.cliAnswers = await this.prompt(cliPrompts)
  }

  writing() {
    var variables = this.config.getAll()
    variables.controller = this.cliAnswers.controller
    variables.command = this.cliAnswers.command

    var source = 'Classes/Command/CommandController.php'
      , target = 'Classes/Command/' + variables.controller + 'CommandController.php'

    this.log('Creating ' + target)
    this.fs.copyTpl(
      this.templatePath(source),
      this.destinationPath(target),
      variables
    )

    var source = this.templatePath('ext_localconf.php')
      , target = this.destinationPath('ext_localconf.php')

    if (this.fs.exists(target) === false) {
      this.fs.write(target, "<?php\n\n")
    }

    this.log('Modifying ' + target)
    var sourceContent = this.fs.read(source)
      , targetContent = this.fs.read(target)

    var regex = /^\/\/ BEGIN$([\s\S]*)^\/\/ END$/gm
      , snippet = regex.exec(sourceContent)

    console.log(sourceContent)
    console.dir(snippet)

    var content = ejs.render(snippet[1], variables)

    this.fs.append(target, content)
  }

  install() {
  }

  end() {
    this.log(chalk.green('Done with everything!'))
  }
}
