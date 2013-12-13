!function (ng, Module) {

	function ensure(obj, key, proto) {
		return obj[key] || (obj[key] = proto);
	}

	function inc(obj, key, val) {
		return obj[key] = (obj[key] || 0) + parseInt(val);
	}

	Module.value('ALL_CONSTITUENCIES', {
		id   : 0,
		name : 'Alle'
	});

	Module.factory('ALL_STATES', ['ALL_CONSTITUENCIES', function (a) {
		return {
			id             : 0,
			name           : 'Alle',
			constituencies : { 0 : a }
		}
	}]);

	Module.service('election', ['ALL_STATES', 'ALL_CONSTITUENCIES', function (ALL_STATES, ALL_CONSTITUENCIES) {

		function Election() {
		}

		Election.prototype.load = function load(data) {
			this.addStates(data.states);
			this.addConstituencies(data.constituencies);
			this.addParties(data.parties);
			this.addMembers(data.members);
			this.addVotes(data.votes);
			this.addSeats(data.seats);
			this.cleanParties();
		};

		// Global   -----------------------------------------------------------

		Election.prototype.getCountry = function getCountry() {
			return ensure(this, 'country', {});
		};

		// Parties   ----------------------------------------------------------

		Election.prototype.getParties = function getParties() {
			return ensure(this, 'parties', {});
		};

		Election.prototype.addParties = function addParties(parties) {
			var map = this.getParties();
			ng.forEach(parties, function (party) {
				map[party.id] = party;
			});
		};

		Election.prototype.getParty = function getParty(id) {
			return this.getParties()[id];
		};

		Election.prototype.cleanParties = function cleanParties() {
			var parties = {};
			ng.forEach(this.getParties(), function (party, id) {
				if (party.votes) parties[id] = party;
			});
			this.parties = parties;
		};

		// States   -----------------------------------------------------------

		Election.prototype.getStates = function getStates() {
			return ensure(this, 'states', { 0 : ALL_STATES });
		};

		Election.prototype.addStates = function addStates(states) {
			var map = this.getStates();
			var country = this.getCountry();

			ng.forEach(states, function (state) {
				inc(country, 'population', state.population);
				map[state.id] = state;
			}, this);
		};

		Election.prototype.getState = function getState(id) {
			return this.getStates()[id];
		};

		// Constituencies   ---------------------------------------------------

		Election.prototype.getConstituencies = function _getConstituencies(state) {
			if (ng.isNumber(state)) state = this.getState(state);
			return ensure(state, 'constituencies', { 0 : ALL_CONSTITUENCIES });
		};

		Election.prototype.addConstituencies = function addConstituencies(constituencies) {
			var country = this.getCountry();
			ng.forEach(constituencies, function (constituency) {
				inc(country, 'electives', constituency.electives);
				inc(country, 'voters', constituency.voters);

				var state = this.getState(constituency.state);
				inc(state, 'electives', constituency.electives);
				inc(state, 'voters', constituency.voters);

				var map = this.getConstituencies(state);
				map[constituency.id] = constituency;
			}, this);
		};

		Election.prototype.getConstituency = function getConstituency(state, id) {
			return this.getConstituencies(state)[id];
		};

		// Members   ----------------------------------------------------------

		Election.prototype.addMembers = function addMembers(members) {
			ng.forEach(members, function (member) {
				member.direct = !!member.constituency;
				member.party = this.getParty(member.party);

				var country = this.getCountry();
				ensure(country, 'members', []).push(member);

				var party = this.getParty(member.party);
				party && ensure(party, 'members', []).push(member);

				var state = this.getState(member.state);
				state && ensure(state, 'members', []).push(member);

				var constituency = this.getConstituency(state, member.constituency);
				constituency && ensure(constituency, 'members', []).push(member);
			}, this);
		};

		// Votes   ------------------------------------------------------------

		Election.prototype.addVotes = function addVotes(results) {
			var country = this.getCountry();
			var countryParties = ensure(country, 'parties', {});

			ng.forEach(results, function (result) {
				var party = this.getParty(result.party);
				inc(ensure(countryParties, result.party, { party : party }), 'votes', result.votes);

				var state = this.getState(result.state);
				var stateParties = ensure(state, 'parties', {});
				inc(ensure(stateParties, result.party, { party : party }), 'votes', result.votes);

				var constituency = this.getConstituency(state, result.constituency);
				var constituencyParties = ensure(constituency, 'parties', {});
				inc(ensure(constituencyParties, result.party, { party : party }), 'votes', result.votes);
			}, this);
		};

		// Seats   ------------------------------------------------------------

		Election.prototype.addSeats = function addSeats(results) {
			var country = this.getCountry();
			var countryParties = ensure(country, 'parties', {});

			console.log(results, results.length);

			ng.forEach(results, function (result) {
				var party = this.getParty(result.party);

				var countryResult = ensure(countryParties, result.party, { party : party });
				inc(countryResult, 'seats', result.seats);
				inc(countryResult, 'overhead', result.overhead);

				var state = this.getState(result.state);
				var stateParties = ensure(state, 'parties', {});
				var stateResult = ensure(stateParties, result.party, { party : party });
				inc(stateResult, 'seats', result.seats);
				inc(stateResult, 'overhead', result.overhead);
			}, this);
		};

		return new Election();

	}]);

}(angular, BTW);
