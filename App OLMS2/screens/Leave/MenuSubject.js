import React, { Component } from 'react';
import {TouchableOpacity, FlatList, AsyncStorage, RefreshControl} from 'react-native'
import { Container, Header, Content, Left, Body, Right, Text, Title, View, Button, ListItem, Card, CardItem } from 'native-base';
import axios from 'axios';
import {Entypo} from '@expo/vector-icons'
import {HOST} from '../host'
export default class ListAvatarExample extends Component {

  constructor(props){
    super(props)
    this.state={
      username: '',
      password: '',
      data: '',
      refreshing: false
    }
    this.setUser();
  }

  setUser = async () => {
    this.setState({
      username: await AsyncStorage.getItem('username'),
      password: await AsyncStorage.getItem('password'),
    });
    this.handleFetch(this.state.username, this.state.password);
  }
  handleFetch = async(username, password) => {
    const subject = await axios({
        method: 'post',
        url: HOST+'mobile',
        data: {
            username,
            password
        }
    });
    this.setState({data: subject.data.subjects});
  }

  _onRefresh = () => {
    this.setState({refreshing: true})
    this.handleFetch(this.state.username, this.state.password)
      .then(() => {
        this.setState({refreshing: false})
      })

  }
  render() {
    const { navigate } = this.props.navigation;
    return (
      <Container>
        <Header>
          <Text>ขอลาเรียน</Text>
        </Header>
        <Content refreshControl={
                <RefreshControl
                  refreshing={this.state.refreshing}
                  onRefresh={this._onRefresh}
                />} >
            <FlatList 
              data={this.state.data}
              renderItem={({item}) => 
                <Card style={{flex: 0}} >
                  <TouchableOpacity onPress={ () => navigate('LeaveformScreen', {name : item.name, id: item.id, code: item.code})}>
                          <CardItem>
                              <Left>
                                <Text>{item.name} ({item.code})</Text>
                              </Left>
                              <Right>
                                  <Entypo style={{fontSize: 30, color: '#2962FF'}} name='chevron-small-right'></Entypo>
                              </Right>
                          </CardItem>
                  </TouchableOpacity>
                </Card>
              }
              keyExtractor={(item, index) => index}
              
            />
        </Content>
      </Container>
    );
  }
}
