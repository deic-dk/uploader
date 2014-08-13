###
# ownCloud
#
# @author Bernhard Posselt
# Copyright (c) 2012 - Bernhard Posselt <nukeawhale@gmail.com>
#
# This file is licensed under the Affero General Public License version 3 or later.
# See the COPYING-README file
#
###

angular.module('Uploader').controller 'ExampleController',
['$scope', 'Config', 'UploaderRequest', '_ExampleController', 'ItemModel',
($scope, Config, UploaderRequest, _ExampleController, ItemModel) ->
	return new _ExampleController($scope, Config, UploaderRequest, ItemModel)
]
