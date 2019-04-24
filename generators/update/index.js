'use strict';

const BaseGenerator = require('../base.js');
const yosay = require('yosay');
const chalk = require('chalk');
const ejs = require('ejs');

module.exports = class extends BaseGenerator {
  async prompting() {
    await super._promptingBasic();
    await this._promptingCli();
  }

  async _promptingCli() {
    if (!this.options['skip-welcome-message']) {
      this.log(
        yosay(
          "Alright! Let's create some update task for our TYPO3 extension together, shall we?"
        )
      );
    }

    var cliPrompts = [
      {
        type: 'input',
        name: 'UpdateName',
        message: 'Name of update task?'
      }
    ];

    this.cliAnswers = await this.prompt(cliPrompts);
  }

  writing() {
    var variables = this.config.getAll();
    variables.UpdateName = this.cliAnswers.UpdateName.toUpperCamelCase();

    var source;
    var target;

    if (
      variables.t3_version === '6.2.0' ||
      variables.t3_version === '7.6.0' ||
      variables.t3_version === '8.7.0'
    ) {
      // File 1
      source = 'Classes/Updates/Update.php';
      target = 'Classes/Updates/' + variables.UpdateName + 'Update.php';

      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);
    } else {
      // File 1
      source = 'Classes/Updates/Update-v9.php';
      target = 'Classes/Updates/' + variables.UpdateName + 'Update.php';

      this.log('Creating ' + target);
      this.fs.copyTpl(this.templatePath(source), this.destinationPath(target), variables);
    }
    // File 2
    source = this.templatePath('ext_localconf.php');
    target = this.destinationPath('ext_localconf.php');

    if (this.fs.exists(target) === false) {
      this.fs.write(target, '<?php\n\n');
    }

    this.log('Modifying ' + target);
    var sourceContent = this.fs.read(source);
    // Var targetContent = this.fs.read(target);

    var regex = /^\/\/ BEGIN$([\s\S]*)^\/\/ END$/gm;
    var snippet = regex.exec(sourceContent);

    var content = ejs.render(snippet[1], variables);

    this.fs.append(target, content);
  }

  install() {}

  end() {
    this.log(chalk.green('Done with everything!'));
  }
};
