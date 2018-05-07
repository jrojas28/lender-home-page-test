<template>
    <div>
        <h2>Teams</h2>
        <hr>
        <div class="teams-container">
            <div class="team-card">
                <div class="team-card__content">
                    <h3><input class="team-card__input" type="text" v-model="newTeam.name" placeholder="Team Name..."></h3>
                    <hr>
                    <ul class="team-card__player-list">
                        <li class="team-card__player" v-for="player in newTeam.players" v-bind:key="player.id">
                            <span>{{ player.first_name + " " + player.last_name }}</span>
                            <button class="team-card__button" v-on:click="removePlayer($event, player)">Remove</button>
                        </li>
                    </ul>
                    <div class="team-card__select-content">
                        <select class="team-card__select" v-on:change="addPlayer" v-model="selectedAgent">
                            <option disabled value="">
                                Select Your Players..
                            </option>
                            <option v-for="(freeAgent) in freeAgents" v-bind:key="freeAgent.id" v-bind:value="freeAgent">
                                {{ freeAgent.first_name + " " + freeAgent.last_name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="team-card__buttons">
                    <button class="team-card__button" type="submit" v-on:click="createTeam($event)">CREATE</button>
                </div>
            </div>
            <div class="team-card" v-for="team in teams" v-bind:key="team.id">
                <div class="team-card__content">
                    <h3><input class="team-card__input" type="text" v-model="team.name"></h3>
                    <hr>
                    <ul class="team-card__player-list" v-if="team.players">
                        <li class="team-card__player" v-for="player in team.players" v-bind:key="player.id">
                            <span>{{ player.first_name + " " + player.last_name }}</span>
                            <button class="team-card__button" v-on:click="removePlayer($event, player, team)">Remove</button>
                        </li>
                    </ul>
                    <div class="team-card__select-content">
                        <select class="team-card__select" v-on:change="addPlayer($event, team)" v-model="selectedAgent">
                            <option disabled value="">
                                Add Players to Team..
                            </option>
                            <option v-for="freeAgent in freeAgents" v-bind:key="freeAgent.id" v-bind:value="freeAgent">
                                {{ freeAgent.first_name + " " + freeAgent.last_name }}
                            </option>
                            <option v-for="exPlayer in team.exPlayers" v-bind:key="exPlayer.id" v-bind:value="exPlayer">
                                {{ exPlayer.first_name + " " + exPlayer.last_name }}
                            </option>
                        </select>
                    </div>
                </div>
                <div class="team-card__buttons">
                    <button class="team-card__button-red" v-on:click="deleteTeam($event, team.id)">Delete</button>
                    <button class="team-card__button" v-on:click="updateTeam($event, team)">Update</button>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                teams: [],
                team_id: '',
                newTeam: {
                    name: '',
                    players: [],
                },
                freeAgents: [],
                selectedAgent: '',
            }
        },
        created() {
            this.fetchTeams();
            this.fetchFreeAgents();
        },
        methods: {
            fetchTeams: function() {
                fetch('/api/teams')
                .then(response => response.json())
                .then(response => {
                    this.teams = response.data;
                });
            },
            fetchFreeAgents: function() {
                fetch('/api/players/freeAgents')
                .then(response => response.json())
                .then(response => {
                    this.freeAgents = response.data;
                });
            },
            createTeam: function(event) {
                event.preventDefault();
                fetch('/api/teams', {
                    method: 'POST',
                    body: JSON.stringify(this.newTeam),
                    headers: {
                        'content-type': 'application/json',
                    }
                })
                .then(res => res.json())
                .then(data => {
                    this.newTeam.name = '';
                    this.newTeam.players = [];
                    alert("Team Created");
                    this.fetchTeams();
                    this.fetchFreeAgents();
                })
                .catch(err => console.log(err));
            },
            updateTeam: function(event, team) {
                event.preventDefault();
                fetch('/api/teams/' + team.id, {
                        method: 'PUT',
                        body: JSON.stringify(team),
                        headers: {
                            'content-type': 'application/json',
                        }
                    })
                    .then(res => res.json())
                    .then(data => {
                        this.newTeam.name = '';
                        this.newTeam.players = [];
                        alert("Team Updated");
                        this.fetchTeams()
                        this.fetchFreeAgents();
                    })
                    .catch(err => console.log(err));

            },
            deleteTeam: function(event, id) {
                if(confirm('Are you sure?')) {
                    fetch('/api/teams/' + id, {
                        method: 'DELETE',
                    })
                    .then(res => res.json())
                    .then(data => {
                        alert("Team Removed. Players are now Free Agents.");
                        this.fetchTeams();
                        this.fetchFreeAgents();
                    })
                    .catch(err => console.log(err));
                }
            },
            addPlayer: function(event, team = null) {
                if(team) {
                    if(!this.selectedAgent.isExPlayer || this.selectedAgent.isExPlayer === false) {
                        this.selectedAgent.isFreeAgent = true;
                    }
                    team.players.push(this.selectedAgent);
                }
                else {
                    this.newTeam.players.push(this.selectedAgent);
                }

                if(team && this.selectedAgent.isExPlayer === true) {
                    team.exPlayers.splice(team.exPlayers.indexOf(this.selectedAgent), 1);
                }
                else {
                    this.freeAgents.splice(this.freeAgents.indexOf(this.selectedAgent), 1);
                }
                this.selectedAgent = '';
            },
            removePlayer: function(event, player, team = null) {
                event.preventDefault();
                
                if(team) {
                    if(player.isFreeAgent) {
                        this.freeAgents.push(player);
                    }
                    else {
                        if (!team.exPlayers) {
                            team.exPlayers = [];
                        }
                        player.isExPlayer = true;
                        team.exPlayers.push(player);
                    }
                    team.players.splice(team.players.indexOf(player), 1);
                }
                else {
                    this.freeAgents.push(player);
                    this.newTeam.players.splice(this.newTeam.players.indexOf(player), 1);
                }
            }
        }
    }
</script>