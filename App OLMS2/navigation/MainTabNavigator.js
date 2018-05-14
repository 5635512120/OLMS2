import React from 'react';
import { Platform } from 'react-native';
import { Ionicons } from '@expo/vector-icons';
import { TabNavigator, TabBarBottom } from 'react-navigation';
import { Badge, Text, Root } from 'native-base';
import Colors from '../constants/Colors';

import HomeScreen from '../screens/HomeScreen';
import LinksScreen from '../screens/LinksScreen';
import StatusScreen from '../screens/StatusScreen';
import NotificationsScreen from '../screens/NotificationsScreen'

import LoginScreen from '../screens/LoginScreen'



export default class MainTabNavigator extends React.Component {
  static navigationOptions = {
    header:null
  }
  constructor(props){
    super(props);
    this.state = {
      isLogin: false
    }
  }

  handleLogin = () => {
		this.setState({ isLogin: true });
	}

	handleLogout = () => {
    this.setState({ isLogin: false});
  }

  render() {  
    
    const TabNavigators = TabNavigator(
      {
        Home: {
          screen: props => <HomeScreen handleLogout={this.handleLogout} {...props}/> ,
        },
        Subjects: {
          screen: LinksScreen,
        },
        Notifications: {
          screen: NotificationsScreen,
        },
        Status: {
          screen: StatusScreen,
        },
      },
      
      {
        navigationOptions: ({ navigation }) => ({
          header:null,
          tabBarIcon: ({ focused }) => {
            const { routeName } = navigation.state;
            let iconName;
            switch (routeName) {
              case 'Home':
                iconName =
                  Platform.OS === 'ios'
                    ? `ios-home${focused ? '' : '-outline'}`
                    : 'md-home';
                break;
              case 'Subjects':
                iconName = 
                  Platform.OS === 'ios' 
                    ? `ios-book${focused ? '' : '-outline'}` 
                    : 'md-book';
                break;
              case 'Notifications':
                  iconName =
                  Platform.OS === 'ios' 
                    ? `ios-notifications${focused ? '' : '-outline'}` 
                    : 'md-notifications';
                  break;
              case 'Status':
                iconName =
                  Platform.OS === 'ios' 
                    ? `ios-options${focused ? '' : '-outline'}` 
                    : 'md-options';
                  break;
            }
            return (
              <Ionicons
                name={iconName}
                size={28}
                style={{ marginBottom: -3 }}
                color={focused ? Colors.tabIconSelected : Colors.tabIconDefault}
              />
            );
          },
        }
      ),
        tabBarComponent: TabBarBottom,
        tabBarPosition: 'bottom',
        animationEnabled: false,
        swipeEnabled: false,
      },
    )

    if (!this.state.isLogin) {
      return  <Root>
                <LoginScreen handleLogin={this.handleLogin}/>
              </Root>
    } 
    return  <Root>
              <TabNavigators />
            </Root>
  }
}
