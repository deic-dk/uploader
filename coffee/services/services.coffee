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

angular.module('Uploader').factory 'UploaderRequest',
['$http', 'Config', '_UploaderRequest', 'Publisher',
($http, Config, _UploaderRequest, Publisher) ->
	return new _UploaderRequest($http, Config, Publisher)
]

angular.module('Uploader').factory 'ItemModel',
['_ItemModel',
(_ItemModel) ->
	return new _ItemModel()
]

angular.module('Uploader').factory 'Publisher',
['_Publisher', 'ItemModel',
(_Publisher, ItemModel) ->
	publisher = new _Publisher()
	publisher.subscribeModelTo(ItemModel, 'items')
	return publisher
]
