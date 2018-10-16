'use strict';

const BaseGenerator = require('../base.js');
const yosay = require('yosay');
const chalk = require('chalk');
const ejs = require('ejs');

module.exports = class extends BaseGenerator {
  async prompting() {
    //await super._promptingBasic();
    await this._promptingCli();
  }

  async _promptingCli() {
    if (!this.options['skip-welcome-message']) {
      this.log(
        yosay(
          "Alright! Let's create some model for our TYPO3 extension together, shall we?"
        )
      );
    }

    let models = this.config.get('models');
    if (models !== undefined) {
      this.log('These models are already in the config:');
      console.dir(models);
    } else {
      models = {};
    }

    this.model = await this.prompt([{
        type: 'input',
        name: 'name',
        message: 'Name of model?'
    }]);

    this.model.properties = [];

    do {
      var property = await this.prompt([
        {
          type: 'input',
          name: 'name',
          message: 'Name of the property?'
        },
        {
          type: 'list',
          name: 'type',
          message: 'Type of the property?',
          choices: ['string', 'integer', 'date', 'datetime'],
          default: 'string'
        },
        {
          type: 'confirm',
          name: 'continue',
          message: 'More properties?',
          default: true
        }
      ]);
      this.model.properties.push({
        name: property.name,
        type: property.type
      });
    } while (property.continue);

console.dir(this.model);

    models[this.model.name] = this.model.properties;
    this.config.set('models', models);
  }

  writing() {
    var variables = this.config.getAll();
    console.dir(this.model);
  }

  install() {}

  end() {
    this.log(chalk.green('Done with everything!'));
  }
};
