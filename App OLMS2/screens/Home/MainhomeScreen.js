import React from 'react';
import {
  Image,
  Platform,
  ScrollView,
  StyleSheet,
  TouchableOpacity,
  View,
  FlatList,
  AsyncStorage,
  RefreshControl
} from 'react-native';
import {WebBrowser} from 'expo';
import { StackNavigator } from 'react-navigation';
import News from './News';
import SubjectScreen from '../Subject/SubjectScreen';
import { Container, Header, Content, Item, Input, Icon, Button, Text, Left, ListItem } from 'native-base';
import axios from 'axios';
import {SimpleLineIcons, Entypo} from '@expo/vector-icons';
import {HOST} from '../host'

export default class HomeScreen extends React.Component {
  constructor(props) {
    super(props);
    this.state = {
      search : false,
      searchSub : "",
      refreshing: false,
      searchs:"",
    };
  }

  search = async(event) => {
    this.setState({
      search : await true,
      searchSub : await event,
    });
    this.searchSub(this.state.username, this.state.password, this.state.searchSub);
  }

  searchSub = async(username, password, search) => {
    const subject = await axios({
      method: 'post',
      url: HOST+'mobileSearch',
        data: {
            username,
            password,
            search
        }
    });
    this.setState({searchs: await subject.data.subjects});
    //console.log(this.state.searchs)
  }

  cancle = () => {
    this.setState({
      search : false,
      searchSub : ''
    });
  }
  
  handalNews = async(username, password) => {
    if (this.state.role == 'student') {
      const subject = await axios({
        method: 'post',
        url: HOST+'mobilefeedNews',
          data: {
              username,
              password
          }
      });
        this.setState({subjects: await subject.data.subjects});
    } else if (this.state.role == 'teacher') {
      const subject = await axios({
        method: 'post',
        url: HOST+'teacherNews',
          data: {
              username,
              password
          }
      });
        this.setState({subjects: await subject.data.subjects});
    }
  }

  _onRefresh = () => {
    this.setState({refreshing: true})
    this.handalNews(this.state.username, this.state.password)
      .then(() => {
        this.setState({refreshing: false})
      })
  }

  componentDidMount = async() => {
    this.setState({
      username: await AsyncStorage.getItem('username'),
      password: await AsyncStorage.getItem('password'),
      role: await AsyncStorage.getItem('role')
    });
    this.handalNews(this.state.username, this.state.password)
  }

  
  render() {
    const {navigate} = this.props.navigation;
    const display = this.state.search ? <FlatList data={this.state.searchs}
                                          renderItem={({item}) =>
                                            <ListItem onPress={ () => navigate('SubjectScreen', {name: item.name, id: item.id, code: item.code})}>
                                              <Text>{item.name}</Text>
                                            </ListItem>
                                          }
                                          keyExtractor={(item, index) => index}  
                                        /> 
                                        : <News data={this.state.subjects} goSubject={navigate}/>
    let button = this.state.search ? <Entypo style={{color: 'gray'}} name='circle-with-cross' onPress={this.cancle} size={20}/> : null;
    return (
      <Container>
        <Header searchBar rounded> 
          <Item >
              <SimpleLineIcons size={20} name='logout' onPress={this.props.logout}/>
              <Input placeholder="Search" onChangeText={this.search}/>
              {button}
              <Text> </Text>
          </Item>
        </Header>
        <Content refreshControl={
                <RefreshControl
                  refreshing={this.state.refreshing}
                  onRefresh={this._onRefresh}
                />}>
          {display}
        </Content>
      </Container>
    );
  }
}